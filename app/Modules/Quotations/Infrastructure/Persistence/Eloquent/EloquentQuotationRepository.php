<?php

namespace App\Modules\Quotations\Infrastructure\Persistence\Eloquent;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use App\Modules\Products\Domain\Models\Product;
use App\Modules\Products\Domain\ValueObjects\PriceCurrency;
use App\Modules\Quotations\Domain\Models\{
    Quotation,
    QuotationProduct,
    QuotationProductAccessory,
};
use App\Modules\Quotations\Domain\Repositories\QuotationRepositoryInterface;
use App\Modules\Quotations\Domain\Services\{
    QuotationTotalsCalculator,
    ProductPricingService,
    QuotationAccessoryService,
    QuotationNumberingService,
};
use App\Modules\Quotations\Domain\ValueObjects\QuotationStatus;

class EloquentQuotationRepository implements QuotationRepositoryInterface
{
    public function __construct(
        private readonly QuotationTotalsCalculator $calculator,
//        private readonly ProductSnapshotService $snapshotService,
        private readonly ProductPricingService $pricingService,
        private readonly QuotationAccessoryService $accessoryService,
        private readonly QuotationNumberingService $numberingService,
    ) {
    }

    public function getOrCreateDraft(Model $user): Quotation
    {
        return Quotation::firstOrCreate([
            'supplier_id' => $user->company->supplier->id,
            'status' => QuotationStatus::DRAFT
        ], [
            'company_id' => $user->company->id,
            'status' => QuotationStatus::DRAFT,
            'currency' => PriceCurrency::EGP,
            'reference' => $this->numberingService->generateReference(),
        ]);
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
                'general_notes',
                'warranty',
                'warranty_and_payments',
                'discount_applied',
                'vat_applied',
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
            // Get position and item_ref info before creating
            $itemInfo = $this->numberingService->getNextItemInfo($quotation);

            $payload = $this->prepareProductPayload(
                $quotation,
                $product,
                array_merge($attributes, $itemInfo)
            );

            /** @var QuotationProduct $item */
            $item = $quotation->products()->create($payload);

            // Included accessories
            $includedPayloads = $this->accessoryService
                ->buildIncludedAccessoriesPayloads($item, $product);

            $providedPayloads = $this->accessoryService
                ->buildProvidedAccessoriesPayloads($item, $accessories);

            $allPayloads = array_merge($includedPayloads, $providedPayloads);

            if (!empty($allPayloads)) {
                $item->accessories()->createMany($allPayloads);
            }

            return $item->load('accessories');
        });
    }

    public function replaceProduct(QuotationProduct $current, Product $replacement, array $attributes, array $accessories = []): QuotationProduct
    {
        return DB::transaction(function () use ($current, $replacement, $attributes, $accessories): QuotationProduct {
            $quotation = $current->quotation;

            // Preserve item_ref and position from current item
            if (! array_key_exists('item_ref', $attributes) && $current->item_ref !== null) {
                $attributes['item_ref'] = $current->item_ref;
            }

            if (! array_key_exists('position', $attributes) && $current->position !== null) {
                $attributes['position'] = $current->position;
            }

            $current->delete();

            $payload = $this->prepareProductPayload($quotation, $replacement, $attributes);

            /** @var QuotationProduct $item */
            $item = $quotation->products()->create($payload);

            // Included accessories
            $includedPayloads = $this->accessoryService->buildIncludedAccessoriesPayloads($item, $replacement);
            if ($includedPayloads !== []) {
                $item->accessories()->createMany($includedPayloads);
            }

            // Provided accessories from request
            $startPosition = DB::table('quotation_product_accessories')
                ->where('quotation_product_id', $item->getKey())
                ->whereNull('deleted_at')
                ->max('position') ?? 0;
            $providedPayloads = $this->accessoryService->buildProvidedAccessoriesPayloads($item, $accessories, (int) $startPosition);
            if ($providedPayloads !== []) {
                $item->accessories()->createMany($providedPayloads);
            }

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
                $allowed['total'] = $this->pricingService->calculateLineTotal(
                    $allowed['price'] ?? $item->price,
                    $allowed['quantity'] ?? $item->quantity,
                    $allowed['discount'] ?? $item->discount,
                );
            }

            if ($allowed !== []) {
                $item->fill($allowed);
                $item->save();
            }

            return $item->fresh('accessories');
        });
    }

    public function deleteProduct(QuotationProduct $item): void
    {
        $item->delete();
    }

    public function addAccessory(QuotationProduct $item, Product $accessory, array $attributes): QuotationProductAccessory
    {
        return DB::transaction(function () use ($item, $accessory, $attributes): QuotationProductAccessory {
            // Get next position info
            $nextPosition = $this->numberingService->nextAccessoryPosition($item);
            $nextRef = $this->numberingService->generateAccessoryReference($item);

            $attributes['position'] = $attributes['position'] ?? $nextPosition;
            $attributes['item_ref'] = $attributes['item_ref'] ?? $nextRef;

            $payload = $this->accessoryService->buildAccessoryPayload($item, $accessory, $attributes);

            /** @var QuotationProductAccessory $record */
            $record = $item->accessories()->create($payload);

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
                $allowed['total'] = $this->pricingService->calculateLineTotal(
                    $allowed['price'] ?? $accessory->price,
                    $allowed['quantity'] ?? $accessory->quantity,
                    $allowed['discount'] ?? $accessory->discount,
                );
            }

            if ($allowed !== []) {
                $accessory->fill($allowed);
                $accessory->save();
            }

            return $accessory->fresh();
        });
    }

    public function deleteAccessory(QuotationProductAccessory $accessory): void
    {
        $accessory->delete();
    }

    public function refreshTotals(Quotation $quotation): Quotation
    {
        // Use DB aggregation for better performance instead of loading all relations
        $totals = $this->calculator->calculateOptimized($quotation->getKey());

        $quotation->fill($totals);
        $quotation->save();

        return $this->loadRelations($quotation->fresh());
    }

    public function loadRelations(Quotation $quotation): Quotation
    {
        return $quotation->loadMissing($this->relations());
    }

    /**
     * @return array<int, string>
     */
    private function relations(): array
    {
        return [
            'supplier.company',
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

        // Build snapshots & roots via dedicated service
//        $snapshots = $this->snapshotService->buildSnapshots($product);
//        $roots = $snapshots['roots'];
        $priceData = $this->pricingService->resolvePrice($product->prices, $quantity);
        $listPrice = $priceData['list_price'];
        $unitPrice = $attributes['price'] ?? $listPrice;
        $discount = (float) ($attributes['discount'] ?? 0);
        $currency = $attributes['currency'] ?? $priceData['currency'];
        $deliveryUnit = $attributes['delivery_time_unit'] ?? $priceData['delivery_time_unit'];
        $deliveryValue = $attributes['delivery_time_value'] ?? $priceData['delivery_time_value'];
        $vatIncluded = (bool) ($attributes['vat_included'] ?? $priceData['vat_included']);

        $itemRef = $attributes['item_ref'] ?? $this->numberingService->generateItemReference($quotation);
        $position = $attributes['position'] ?? $this->numberingService->nextItemPosition($quotation);
        return [
            'item_ref' => $itemRef,
            'position' => $position,
            'solution_id' => $product->solution_id,
            'department_id' => $product->department_id,
            'subcategory_id' => $product->subcategory_id,
            'family_id' => $product->family_id,
            'supplier_id' => $product->supplier_id,
            'brand_id' => $product->brand?->id,
            'brand_name' => $product->brand?->name ?? '',
            'product_id' => $product->getKey(),
            'product_code' => $product->code,
            'product_name' => $product->name,
            'product_description' => $product->description,
            'product_origin' => $product->origin,
            'notes' => $attributes['notes'] ?? null,
            'delivery_time_unit' => $deliveryUnit,
            'delivery_time_value' => $deliveryValue,
            'vat_included' => $vatIncluded,
            'quantity' => $quantity,
            'list_price' => $listPrice,
            'price' => $unitPrice,
            'discount' => $discount,
            'total' => $this->pricingService->calculateLineTotal($unitPrice, $quantity, $discount),
            'currency' => $currency,
        ];
    }

}
