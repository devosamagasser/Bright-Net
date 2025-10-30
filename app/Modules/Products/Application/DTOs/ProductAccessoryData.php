<?php

namespace App\Modules\Products\Application\DTOs;

use Illuminate\Support\Collection;
use App\Modules\Products\Domain\Models\ProductAccessory;
use App\Modules\Products\Domain\ValueObjects\AccessoryType;

class ProductAccessoryData
{
    /**
     * @param  array<string, mixed>  $product
     */
    private function __construct(
        public readonly string $type,
        public readonly array $product,
    ) {
    }

    public static function fromModel(ProductAccessory $accessory): self
    {
        $product = $accessory->accessory;

        return new self(
            type: $accessory->accessory_type?->value ?? AccessoryType::OPTIONAL->value,
            product: $product ? [
                'id' => (int) $product->getKey(),
                'code' => $product->code,
                'name' => $product->name,
                'translations' => $product->translations
                    ->mapWithKeys(static fn ($translation) => [
                        $translation->locale => [
                            'name' => $translation->name,
                            'description' => $translation->description,
                        ],
                    ])->toArray(),
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
