<?php

namespace App\Modules\Products\Application\DTOs;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use App\Modules\Products\Domain\Models\ProductAccessory;
use App\Modules\Products\Domain\ValueObjects\AccessoryType;

class ProductAccessoryData
{
    /**
     * @param  array<string, mixed>  $product
     */
    private function __construct(
        public readonly string $type,
        public readonly int $quantity,
        public readonly array $product,
    ) {
    }

    public static function fromModel(ProductAccessory $accessory): self
    {
        $product = $accessory->accessory;
        return new self(
            type: $accessory->accessory_type?->value ?? AccessoryType::OPTIONAL->value,
            quantity: $accessory->quantity ?? 1,
            product: $product ? [
                'id' => (int) $product->getKey(),
                'code' => $product->code,
                'name' => $product->name,
                'description' => $product->description,
                'quotation_image' => ProductData::serializeMedia($product, 'quotation_image'),
                'gallery' => ProductData::serializeMedia($product, 'gallery'),
                'values' => $product->fieldValues
                    ->sortBy(static fn ($value) => $value->field?->position ?? 0)
                    ->map(static fn ($value) => ProductValueData::fromModel($value))
                    ->values()
                    ->all(),
                'roots' => [
                    'family_id' => $product->family_id,
                    'department_id' => $product->family->supplier_department_id,
                    'subcategory_id' => $product->family->subcategory_id,
                ],
            ] : [],
        );
    }

    /**
     * @param  Collection<int, ProductAccessory>  $accessories
     * @return array<string, array<int, array<string, mixed>>>
     */
    public static function grouped(Collection $accessories): array
    {
        $grouped = $accessories
            ->map(static fn (ProductAccessory $accessory) => self::fromModel($accessory))
            ->groupBy(static fn (self $data) => $data->type)
            ->map(static fn (Collection $items) => $items
                ->map(static fn (self $data) => $data->product)
                ->filter()
                ->values()
                ->all()
            )
            ->toArray();

        $defaults = [];
        foreach (AccessoryType::values() as $type) {
            $defaults[$type] = $grouped[$type] ?? [];
        }

        return $defaults;
    }
}
