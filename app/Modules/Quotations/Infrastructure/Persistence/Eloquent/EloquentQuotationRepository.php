<?php

namespace App\Modules\Quotations\Infrastructure\Persistence\Eloquent;

use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Collection;
use App\Models\Supplier;
use App\Modules\Products\Domain\Models\Product;
use App\Modules\Products\Domain\Models\ProductAccessory;
use App\Modules\Products\Domain\Models\ProductPrice;
use App\Modules\Products\Domain\ValueObjects\{AccessoryType, PriceCurrency};
use App\Modules\Quotations\Domain\Models\{
    Quotation,
    QuotationProduct,
    QuotationProductAccessory,
};
use App\Modules\Quotations\Domain\Repositories\QuotationRepositoryInterface;
use App\Modules\Quotations\Domain\Services\QuotationTotalsCalculator;
use App\Modules\Quotations\Domain\ValueObjects\QuotationStatus;

class EloquentQuotationRepository implements QuotationRepositoryInterface
{
    public function __construct(
        private readonly QuotationTotalsCalculator $calculator = new QuotationTotalsCalculator(),
    ) {
    }

    public function getOrCreateDraft(int $supplierId): Quotation
    {
        $quotation = Quotation::query()
            ->where('supplier_id', $supplierId)
            ->where('status', QuotationStatus::DRAFT)
            ->first();

        if ($quotation === null) {
            $companyId = Supplier::query()
                ->whereKey($supplierId)
                ->value('company_id');

            $quotation = new Quotation([
                'supplier_id' => $supplierId,
                'company_id' => $companyId,
                'status' => QuotationStatus::DRAFT,
                'currency' => PriceCurrency::EGP,
                'reference' => $this->generateReference(),
            ]);

            $quotation->save();
        }

        return $this->loadRelations($quotation);
    }

    public function update(Quotation $quotation, array $attributes): Quotation
    {
        return DB::transaction(function () use ($quotation, $attributes): Quotation {
            $allowed = Arr::only($attributes, [
                'reference',
                'title',
                'company_id',
                'valid_until',
                'notes',
                'currency',
                'meta',
            ]);

            if ($allowed === []) {
                return $this->loadRelations($quotation);
            }

            $quotation->fill($allowed);
            $quotation->save();

            return $this->loadRelations($quotation);
        });
    }

    public function addProduct(Quotation $quotation, Product $product, array $attributes, array $accessories = []): QuotationProduct
    {
        return DB::transaction(function () use ($quotation, $product, $attributes, $accessories): QuotationProduct {
            $product->loadMissing([
                'prices',
                'family.subcategory.department.solution',
                'family.supplier.company',
                'accessories.accessory.prices',
                'accessories.accessory.family.subcategory.department.solution',
                'accessories.accessory.family.supplier.company',
            ]);

            $payload = $this->prepareProductPayload($quotation, $product, $attributes);

            /** @var QuotationProduct $item */
            $item = $quotation->products()->create($payload);

            $this->attachIncludedAccessories($item, $product);
            // $this->attachNeededAccessories($item, $product);

            $this->createProvidedAccessories($item, $accessories);

            $this->refreshTotals($quotation);

            return $item->load('accessories');
        });
    }

    public function replaceProduct(QuotationProduct $current, Product $replacement, array $attributes, array $accessories = []): QuotationProduct
    {
        return DB::transaction(function () use ($current, $replacement, $attributes, $accessories): QuotationProduct {
            $quotation = $current->quotation;

            if (! array_key_exists('item_ref', $attributes) && $current->item_ref !== null) {
                $attributes['item_ref'] = $current->item_ref;
            }

            if (! array_key_exists('position', $attributes) && $current->position !== null) {
                $attributes['position'] = $current->position;
            }

            $current->delete();

            $replacement->loadMissing([
                'prices',
                'family.subcategory.department.solution',
                'family.supplier.company',
                'accessories.accessory.prices',
                'accessories.accessory.family.subcategory.department.solution',
                'accessories.accessory.family.supplier.company',
            ]);

            $payload = $this->prepareProductPayload($quotation, $replacement, $attributes);

            /** @var QuotationProduct $item */
            $item = $quotation->products()->create($payload);

            $this->attachIncludedAccessories($item, $replacement);
            // $this->attachNeededAccessories($item, $replacement);

            $this->createProvidedAccessories($item, $accessories);

            $this->refreshTotals($quotation);

            return $item->load('accessories');
        });
    }

    public function updateProduct(QuotationProduct $item, array $attributes): QuotationProduct
    {
        return DB::transaction(function () use ($item, $attributes): QuotationProduct {
            $allowed = Arr::only($attributes, [
                'item_ref',
                'position',
                'notes',
                'delivery_time_unit',
                'delivery_time_value',
                'vat_included',
                'quantity',
                'list_price',
                'price',
                'discount',
                'currency',
            ]);

            if ($this->shouldRecalculate($allowed)) {
                $allowed['total'] = $this->calculateLineTotal(
                    $allowed['price'] ?? $item->price,
                    $allowed['quantity'] ?? $item->quantity,
                    $allowed['discount'] ?? $item->discount,
                );
            }
            if ($allowed !== []) {
                $item->fill($allowed);
                $item->save();
            }

            $item->refresh();

            $this->refreshTotals($item->quotation);
            return $item->load('accessories');
        });
    }

    public function deleteProduct(QuotationProduct $item): void
    {
        DB::transaction(function () use ($item): void {
            $quotation = $item->quotation;
            $item->delete();
            $this->refreshTotals($quotation);
        });
    }

    public function addAccessory(QuotationProduct $item, Product $accessory, array $attributes): QuotationProductAccessory
    {
        return DB::transaction(function () use ($item, $accessory, $attributes): QuotationProductAccessory {
            $record = $this->createAccessoryRecord($item, $accessory, $attributes);

            $this->refreshTotals($item->quotation);

            return $record;
        });
    }

    public function updateAccessory(QuotationProductAccessory $accessory, array $attributes): QuotationProductAccessory
    {
        return DB::transaction(function () use ($accessory, $attributes): QuotationProductAccessory {
            $allowed = Arr::only($attributes, [
                'item_ref',
                'position',
                'notes',
                'delivery_time_unit',
                'delivery_time_value',
                'vat_included',
                'quantity',
                'list_price',
                'price',
                'discount',
                'currency',
            ]);

            if ($this->shouldRecalculate($allowed)) {
                $allowed['total'] = $this->calculateLineTotal(
                    $allowed['price'] ?? $accessory->price,
                    $allowed['quantity'] ?? $accessory->quantity,
                    $allowed['discount'] ?? $accessory->discount,
                );
            }

            if ($allowed !== []) {
                $accessory->fill($allowed);
                $accessory->save();
            }

            $accessory->refresh();

            $this->refreshTotals($accessory->quotation);

            return $accessory;
        });
    }

    public function deleteAccessory(QuotationProductAccessory $accessory): void
    {
        DB::transaction(function () use ($accessory): void {
            $quotation = $accessory->quotation;
            $accessory->delete();
            $this->refreshTotals($quotation);
        });
    }

    public function refreshTotals(Quotation $quotation): Quotation
    {
        $quotation->loadMissing($this->relationsForTotals());

        $totals = $this->calculator->calculate($quotation);

        $quotation->fill($totals);
        $quotation->save();

        return $this->loadRelations($quotation);
    }

    public function loadRelations(Quotation $quotation): Quotation
    {
        return $quotation->load($this->relations());
    }

    /**
     * @return array<int, string>
     */
    private function relations(): array
    {
        return [
            'supplier.company',
            'company',
            'products.accessories',
        ];
    }

    /**
     * @return array<int, string>
     */
    private function relationsForTotals(): array
    {
        return [
            'products.accessories',
        ];
    }

    /**
     * @param  array<string, mixed>  $attributes
     */
    private function shouldRecalculate(array $attributes): bool
    {
        return Arr::hasAny($attributes, ['price', 'quantity', 'discount']);
    }

    private function prepareProductPayload(Quotation $quotation, Product $product, array $attributes): array
    {
        $quantity = max(1, (int) ($attributes['quantity'] ?? 1));
        $roots = $this->resolveRoots($product);
        $priceData = $this->resolvePrice($product->prices, $quantity);

        $listPrice = $priceData['list_price'];
        $unitPrice = $attributes['price'] ?? $listPrice;
        $discount = (float) ($attributes['discount'] ?? 0);
        $currency = $attributes['currency'] ?? $priceData['currency'];
        $deliveryUnit = $attributes['delivery_time_unit'] ?? $priceData['delivery_time_unit'];
        $deliveryValue = $attributes['delivery_time_value'] ?? $priceData['delivery_time_value'];
        $vatIncluded = (bool) ($attributes['vat_included'] ?? $priceData['vat_included']);

        $itemRef = $attributes['item_ref'] ?? $this->generateItemReference($quotation);
        $position = $attributes['position'] ?? $this->nextItemPosition($quotation);

        return [
            'item_ref' => $itemRef,
            'position' => $position,
            'solution_id' => $roots['solution_id'],
            'supplier_id' => $roots['supplier_id'],
            'brand_id' => $roots['brand_id'],
            'department_id' => $roots['department_id'],
            'subcategory_id' => $roots['subcategory_id'],
            'family_id' => $roots['family_id'],
            'product_id' => $product->getKey(),
            'product_code' => $product->code,
            'product_name' => $product->name,
            'product_description' => $product->description,
            'product_snapshot' => $this->makeProductSnapshot($product),
            'roots_snapshot' => $this->makeRootsSnapshot($product, $roots),
            'price_snapshot' => $priceData['snapshot'],
            'notes' => $attributes['notes'] ?? null,
            'delivery_time_unit' => $deliveryUnit,
            'delivery_time_value' => $deliveryValue,
            'vat_included' => $vatIncluded,
            'quantity' => $quantity,
            'list_price' => $listPrice,
            'price' => $unitPrice,
            'discount' => $discount,
            'total' => $this->calculateLineTotal($unitPrice, $quantity, $discount),
            'currency' => $currency,
        ];
    }

    private function prepareAccessoryPayload(QuotationProduct $item, Product $accessory, array $attributes): array
    {
        $quantity = max(1, (int) ($attributes['quantity'] ?? 1));
        $type = isset($attributes['accessory_type'])
            ? AccessoryType::from($attributes['accessory_type'])
            : AccessoryType::NEEDED;

        $roots = $this->resolveRoots($accessory);
        $priceData = $this->resolvePrice($accessory->prices, $quantity);

        $listPrice = $type === AccessoryType::INCLUDED ? null : $priceData['list_price'];
        $unitPrice = $type === AccessoryType::INCLUDED
            ? null
            : ($attributes['price'] ?? $listPrice);
        $discount = $type === AccessoryType::INCLUDED
            ? 0.0
            : (float) ($attributes['discount'] ?? 0);

        $currency = $attributes['currency']
            ?? $priceData['currency']
            ?? ($item->currency instanceof PriceCurrency ? $item->currency->value : PriceCurrency::EGP->value);
        $deliveryUnit = $attributes['delivery_time_unit'] ?? $priceData['delivery_time_unit'];
        $deliveryValue = $attributes['delivery_time_value'] ?? $priceData['delivery_time_value'];
        $vatIncluded = (bool) ($attributes['vat_included'] ?? $priceData['vat_included']);

        $itemRef = $attributes['item_ref'] ?? $this->generateAccessoryReference($item);
        $position = $attributes['position'] ?? $this->nextAccessoryPosition($item);

        return [
            'quotation_id' => $item->quotation_id,
            'item_ref' => $itemRef,
            'position' => $position,
            'solution_id' => $roots['solution_id'],
            'supplier_id' => $roots['supplier_id'],
            'brand_id' => $roots['brand_id'],
            'department_id' => $roots['department_id'],
            'subcategory_id' => $roots['subcategory_id'],
            'family_id' => $roots['family_id'],
            'product_id' => $accessory->getKey(),
            'product_code' => $accessory->code,
            'product_name' => $accessory->name,
            'product_description' => $accessory->description,
            'product_snapshot' => $this->makeProductSnapshot($accessory),
            'roots_snapshot' => $this->makeRootsSnapshot($accessory, $roots),
            'price_snapshot' => $type === AccessoryType::INCLUDED ? null : $priceData['snapshot'],
            'notes' => $attributes['notes'] ?? null,
            'delivery_time_unit' => $deliveryUnit,
            'delivery_time_value' => $deliveryValue,
            'vat_included' => $vatIncluded,
            'quantity' => $quantity,
            'list_price' => $listPrice,
            'price' => $unitPrice,
            'discount' => $discount,
            'total' => $this->calculateLineTotal($unitPrice, $quantity, $discount),
            'currency' => $currency,
            'accessory_type' => $type,
        ];
    }

    private function resolveRoots(Product $product): array
    {
        $family = $product->family;
        $subcategory = $family?->subcategory;
        $department = $subcategory?->department;
        $solution = $department?->solution;
        $supplier = $family?->supplier;

        $brand = null;
        if ($supplier !== null && $department !== null) {
            $brand = DB::table('supplier_brands as sb')
                ->join('supplier_solutions as ss', 'sb.supplier_solution_id', '=', 'ss.id')
                ->join('supplier_departments as sd', 'sb.id', '=', 'sd.supplier_brand_id')
                ->join('brands', 'brands.id', '=', 'sb.brand_id')
                ->where('ss.supplier_id', $supplier->getKey())
                ->where('sd.department_id', $department->getKey())
                ->select('brands.id', 'brands.name')
                ->first();
        }

        return [
            'solution_id' => $solution?->getKey(),
            'solution_name' => $solution?->name,
            'department_id' => $department?->getKey(),
            'department_name' => $department?->name,
            'subcategory_id' => $subcategory?->getKey(),
            'subcategory_name' => $subcategory?->name,
            'family_id' => $family?->getKey(),
            'family_name' => $family?->name,
            'supplier_id' => $supplier?->getKey(),
            'supplier_name' => $supplier?->company?->name,
            'brand_id' => $brand->id ?? null,
            'brand_name' => $brand->name ?? null,
        ];
    }

    private function makeRootsSnapshot(Product $product, array $roots): array
    {
        return [
            'solution' => [
                'id' => $roots['solution_id'],
                'name' => $roots['solution_name'],
            ],
            'department' => [
                'id' => $roots['department_id'],
                'name' => $roots['department_name'],
            ],
            'subcategory' => [
                'id' => $roots['subcategory_id'],
                'name' => $roots['subcategory_name'],
            ],
            'family' => [
                'id' => $roots['family_id'],
                'name' => $roots['family_name'],
            ],
            'brand' => [
                'id' => $roots['brand_id'],
                'name' => $roots['brand_name'],
            ],
            'supplier' => [
                'id' => $roots['supplier_id'],
                'name' => $roots['supplier_name'],
            ],
        ];
    }

    private function makeProductSnapshot(Product $product): array
    {
        return [
            'id' => (int) $product->getKey(),
            'code' => $product->code,
            'name' => $product->name,
            'description' => $product->description,
            'stock' => $product->stock,
            'disclaimer' => $product->disclaimer,
            'color' => $product->color,
            'style' => $product->style,
            'manufacturer' => $product->manufacturer,
            'application' => $product->application,
            'origin' => $product->origin,
            'translations' => $product->translations
                ->mapWithKeys(static fn ($translation) => [
                    $translation->locale => [
                        'name' => $translation->name,
                        'description' => $translation->description,
                    ],
                ])->toArray(),
        ];
    }

    /**
     * @param  \Illuminate\Support\Collection<int, ProductPrice>  $prices
     */
    private function resolvePrice(Collection $prices, int $quantity): array
    {
        $price = $prices
            ->first(static fn (ProductPrice $price) => $quantity >= $price->from && $quantity <= $price->to);

        if ($price === null) {
            $price = $prices->sortBy('from')->first();
        }

        if ($price === null) {
            return [
                'list_price' => null,
                'currency' => PriceCurrency::EGP->value,
                'delivery_time_unit' => null,
                'delivery_time_value' => null,
                'vat_included' => false,
                'snapshot' => null,
            ];
        }

        return [
            'list_price' => (float) $price->price,
            'currency' => $price->currency?->value ?? PriceCurrency::EGP->value,
            'delivery_time_unit' => $price->delivery_time_unit?->value,
            'delivery_time_value' => $price->delivery_time_value,
            'vat_included' => (bool) $price->vat_status,
            'snapshot' => [
                'id' => (int) $price->getKey(),
                'from' => $price->from,
                'to' => $price->to,
                'price' => (float) $price->price,
                'currency' => $price->currency?->value,
                'delivery_time_unit' => $price->delivery_time_unit?->value,
                'delivery_time_value' => $price->delivery_time_value,
                'vat_status' => (bool) $price->vat_status,
            ],
        ];
    }

    private function calculateLineTotal(?float $price, int $quantity, float $discount): float
    {
        if ($price === null) {
            return 0.0;
        }

        $subtotal = $price * $quantity;
        $discountValue = $subtotal * ($discount / 100);

        return round($subtotal - $discountValue, 2);
    }

    private function generateReference(): string
    {
        $prefix = 'Q' . now()->format('ymd');
        $sequence = (int) (Quotation::query()
            ->where('reference', 'like', $prefix . '%')
            ->count() + 1);

        return sprintf('%s-%04d', $prefix, $sequence);
    }

    private function generateItemReference(Quotation $quotation): string
    {
        $sequence = (int) ($quotation->products()->count() + 1);

        return sprintf('P-%03d', $sequence);
    }

    private function generateAccessoryReference(QuotationProduct $item): string
    {
        $sequence = (int) ($item->accessories()->count() + 1);

        return sprintf('%s-A%02d', $item->item_ref ?? 'P', $sequence);
    }

    private function nextItemPosition(Quotation $quotation): int
    {
        $position = $quotation->products()->max('position');

        return $position === null ? 1 : $position + 1;
    }

    private function nextAccessoryPosition(QuotationProduct $item): int
    {
        $position = $item->accessories()->max('position');

        return $position === null ? 1 : $position + 1;
    }

    private function attachIncludedAccessories(QuotationProduct $item, Product $product): void
    {
        $product->accessories
            ->filter(static fn (ProductAccessory $accessory) => $accessory->accessory_type === AccessoryType::INCLUDED)
            ->each(function (ProductAccessory $accessory) use ($item): void {
                $linked = $accessory->accessory;

                if ($linked === null) {
                    return;
                }

                $quantity = $this->normalizeQuantity($accessory->quantity);

                $linked->loadMissing([
                    'prices',
                    'family.subcategory.department.solution',
                    'family.supplier.company',
                ]);

                $payload = $this->prepareAccessoryPayload(
                    $item,
                    $linked,
                    [
                        'quantity' => $quantity * $item->quantity,
                        'accessory_type' => AccessoryType::INCLUDED->value,
                        'price' => null,
                        'discount' => 0,
                    ]
                );

                $item->accessories()->create($payload);
            });
    }

    private function attachNeededAccessories(QuotationProduct $item, Product $product): void
    {
        $product->accessories
            ->filter(static fn (ProductAccessory $accessory) => $accessory->accessory_type === AccessoryType::NEEDED)
            ->each(function (ProductAccessory $accessory) use ($item): void {
                $linked = $accessory->accessory;

                if ($linked === null) {
                    return;
                }

                $quantity = $this->normalizeQuantity($accessory->quantity);

                $linked->loadMissing([
                    'prices',
                    'family.subcategory.department.solution',
                    'family.supplier.company',
                ]);

                $payload = $this->prepareAccessoryPayload(
                    $item,
                    $linked,
                    [
                        'quantity' => $quantity,
                        'accessory_type' => AccessoryType::NEEDED->value,
                    ]
                );

                $item->accessories()->create($payload);
            });
    }

    /**
     * @param  array<int, array{product: Product, attributes: array<string, mixed>}>  $accessories
     */
    private function createProvidedAccessories(QuotationProduct $item, array $accessories): void
    {
        foreach ($accessories as $accessoryData) {
            $this->createAccessoryRecord($item, $accessoryData['product'], $accessoryData['attributes']);
        }
    }

    /**
     * @param  array<string, mixed>  $attributes
     */
    private function createAccessoryRecord(QuotationProduct $item, Product $accessory, array $attributes): QuotationProductAccessory
    {
        $accessory->loadMissing([
            'prices',
            'family.subcategory.department.solution',
            'family.supplier.company',
        ]);

        $payload = $this->prepareAccessoryPayload($item, $accessory, $attributes);

        /** @var QuotationProductAccessory $record */
        $record = $item->accessories()->create($payload);

        return $record;
    }

    private function normalizeQuantity(?string $value): int
    {
        if ($value !== null && is_numeric($value)) {
            return max(1, (int) $value);
        }

        return 1;
    }
}
