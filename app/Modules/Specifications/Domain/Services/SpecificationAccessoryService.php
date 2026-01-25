<?php

namespace App\Modules\Specifications\Domain\Services;

use App\Modules\Products\Domain\Models\Product;
use App\Modules\Specifications\Domain\Models\SpecificationItem;

class SpecificationAccessoryService
{
    /**
     * Build payloads for user-provided accessories (from request).
     *
     * @param  array<int, array{product: Product, attributes: array<string, mixed>}>  $accessories
     * @return array<int, array<string, mixed>>
     */
    public function buildProvidedAccessoriesPayloads(SpecificationItem $item, array $accessories): array
    {
        if ($accessories === []) {
            return [];
        }

        $payloads = [];

        foreach ($accessories as $accessoryData) {
            $accessory = $accessoryData['product'];
            $attributes = $accessoryData['attributes'];

            $attributes['item_ref'] = $attributes['item_ref']
                ?? sprintf('%s-A%02d', $item->item_ref ?? 'P', $item->position);

            $payloads[] = $this->buildAccessoryPayload($item, $accessory, $attributes);
        }

        return $payloads;
    }

    /**
     * Build payload for single accessory.
     *
     * @param  array<string, mixed>  $attributes
     * @return array<string, mixed>
     */
    public function buildAccessoryPayload(SpecificationItem $item, Product $accessory, array $attributes): array
    {
        $quantity = max(1, (int) ($attributes['quantity'] ?? 1));

        $itemRef = $attributes['item_ref'] ?? sprintf('%s-A%02d', $item->item_ref ?? 'P', 1);
        $position = $attributes['position'] ?? 1;

        return [
            'specification_id' => $item->specification_id,
            'spec_product_id' => $item->getKey(),
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
            'quantity' => $quantity,
            'accessory_type' => $attributes['accessory_type'] ?? 'OPTIONAL',
        ];
    }
}


