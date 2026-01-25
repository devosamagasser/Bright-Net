<?php

namespace App\Modules\Quotations\Domain\Services;

use App\Modules\PriceRules\Domain\ValueObjects\PriceCurrency;
use App\Modules\Products\Domain\Models\Product;
use App\Modules\Products\Domain\Models\ProductAccessory;
use App\Modules\Products\Domain\ValueObjects\AccessoryType;
use App\Modules\Quotations\Domain\Models\{QuotationProduct,};

class QuotationAccessoryService
{
    public function __construct(
//        private readonly ProductSnapshotService $snapshotService,
        private readonly ProductPricingService $pricingService,
    ) {
    }

    /**
     * Build payloads for INCLUDED accessories (auto attached).
     *
     * @return array<int, array<string, mixed>>
     */
    public function buildIncludedAccessoriesPayloads(QuotationProduct $item, Product $product): array
    {
        // Ensure accessories are loaded with their relations
        $product->loadMissing([
            'accessories.accessory.brand',
        ]);

        $includedAccessories = $product->accessories
            ->filter(static fn (ProductAccessory $accessory) =>
                $accessory->accessory_type === AccessoryType::INCLUDED
            );

        if ($includedAccessories->isEmpty()) {
            return [];
        }

        $payloads = [];
        $position = 1;

        foreach ($includedAccessories as $accessory) {
            $linked = $accessory->accessory;

            if ($linked === null) {
                continue;
            }

            $quantity = $this->normalizeQuantity($accessory->quantity);

            $payloads[] = $this->buildAccessoryPayload(
                $item,
                $linked,
                [
                    'quantity' => $quantity * $item->quantity,
                    'accessory_type' => AccessoryType::INCLUDED->value,
                    'price' => null,
                    'discount' => 0,
                    'position' => $position,
                    'item_ref' => sprintf('%s-A%02d', $item->item_ref ?? 'P', $position),
                ]
            );

            $position++;
        }

        return $payloads;
    }

    /**
     * Build payloads for NEEDED accessories defined on product.
     *
     * @return array<int, array<string, mixed>>
     */
    public function buildNeededAccessoriesPayloads(QuotationProduct $item, Product $product, int $startPosition): array
    {
        $product->loadMissing([
            'accessories.accessory.prices',
            'accessories.accessory.family.subcategory.department.solution',
            'accessories.accessory.family.supplier.company',
            'accessories.accessory.brand',
            'accessories.accessory.translations',
        ]);

        $neededAccessories = $product->accessories
            ->filter(static fn (ProductAccessory $accessory) => $accessory->accessory_type === AccessoryType::NEEDED);

        if ($neededAccessories->isEmpty()) {
            return [];
        }

        $payloads = [];
        $position = $startPosition + 1;

        foreach ($neededAccessories as $accessory) {
            $linked = $accessory->accessory;

            if ($linked === null) {
                continue;
            }

            $quantity = $this->normalizeQuantity($accessory->quantity);

            $payloads[] = $this->buildAccessoryPayload(
                $item,
                $linked,
                [
                    'quantity' => $quantity,
                    'accessory_type' => AccessoryType::NEEDED->value,
                    'position' => $position,
                    'item_ref' => sprintf('%s-A%02d', $item->item_ref ?? 'P', $position),
                ]
            );

            $position++;
        }

        return $payloads;
    }

    /**
     * Build payloads for user-provided accessories (from request).
     *
     * @param  array<int, array{product: Product, attributes: array<string, mixed>}>  $accessories
     * @return array<int, array<string, mixed>>
     */
    public function buildProvidedAccessoriesPayloads(QuotationProduct $item, array $accessories): array
    {
        if ($accessories === []) {
            return [];
        }

        $payloads = [];
//        $position = $startPosition + 1;

        foreach ($accessories as $accessoryData) {
            $accessory = $accessoryData['product'];
            $attributes = $accessoryData['attributes'];

            // Ensure accessory has required relations loaded
//            $accessory->loadMissing([
//                'prices',
//                'family.subcategory.department.solution',
//                'family.supplier.company',
//                'translations',
//            ]);

//            $attributes['position'] = $attributes['position'] ?? $position;
            $attributes['item_ref'] = $attributes['item_ref'] ?? sprintf('%s-A%02d', $item->item_ref ?? 'P', $item->position);

            $payloads[] = $this->buildAccessoryPayload($item, $accessory, $attributes);
//            $position++;
        }

        return $payloads;
    }

    /**
     * Build payload for single accessory (used by repository for add/update).
     *
     * @param  array<string, mixed>  $attributes
     * @return array<string, mixed>
     */
    public function buildAccessoryPayload(QuotationProduct $item, Product $accessory, array $attributes): array
    {
        $quantity = max(1, (int) ($attributes['quantity'] ?? 1));
        $type = isset($attributes['accessory_type'])
            ? AccessoryType::from($attributes['accessory_type'])
            : AccessoryType::NEEDED;

//        $snapshots = $this->snapshotService->buildSnapshots($accessory);
//        $roots = $snapshots['roots'];
        $priceData = $this->pricingService->resolvePrice($accessory->prices, $quantity);

        $listPrice = $type === AccessoryType::INCLUDED ? null : $priceData['list_price'];
        $unitPrice = $type === AccessoryType::INCLUDED
            ? null
            : ($attributes['price'] ?? $listPrice);
        $discount = $type === AccessoryType::INCLUDED
            ? 0.0
            : (float) ($attributes['discount'] ?? 0);

        $currency = $attributes['currency']
            ?? $priceData['currency']
            ?? ($item->currency?->value ?? PriceCurrency::EGP->value);
        $deliveryUnit = $attributes['delivery_time_unit'] ?? $priceData['delivery_time_unit'];
        $deliveryValue = $attributes['delivery_time_value'] ?? $priceData['delivery_time_value'];
        $vatIncluded = (bool) ($attributes['vat_included'] ?? $priceData['vat_included']);

        $itemRef = $attributes['item_ref'] ?? sprintf('%s-A%02d', $item->item_ref ?? 'P', 1);
        $position = $attributes['position'] ?? 1;

        return [
            'quotation_id' => $item->quotation_id,
            'item_ref' => $itemRef,
            'position' => $position,
            'solution_id' => $accessory->solution_id,
            'department_id' => $accessory->department_id,
            'subcategory_id' => $accessory->subcategory_id,
            'family_id' => $accessory->family_id,
            'supplier_id' => $accessory->supplier_id,
            'brand_id' => $accessory->brand_id,
            'brand_name' => $accessory->brand?->name ?? '',
            'product_id' => $accessory->getKey(),
            'product_code' => $accessory->code,
            'product_name' => $accessory->name,
            'product_description' => $accessory->description,
            'product_origin' => $accessory->origin,
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
            'accessory_type' => $type,
        ];
    }

    private function normalizeQuantity(?string $value): int
    {
        if ($value !== null && is_numeric($value)) {
            return max(1, (int) $value);
        }

        return 1;
    }
}


