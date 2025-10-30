<?php

namespace App\Modules\Products\Application\DTOs;

use Illuminate\Support\Collection;
use App\Modules\Products\Domain\Models\Product;

class ProductData
{
    /**
     * @param  array<string, mixed>  $attributes
     * @param  array<string, array<string, mixed>>  $translations
     * @param  array<int, ProductValueData>  $values
     * @param  array<int, ProductPriceData>  $prices
     * @param  array<string, array<int, array<string, mixed>>>  $accessories
     * @param  array<int, array<string, mixed>>  $colors
     * @param  array<string, array<int, array<string, mixed>>>  $media
     */
    private function __construct(
        public readonly array $attributes,
        public readonly array $translations,
        public readonly array $values,
        public readonly array $prices,
        public readonly array $accessories,
        public readonly array $colors,
        public readonly array $media,
    ) {
    }

    public static function fromModel(Product $product): self
    {
        $product->loadMissing([
            'translations',
            'fieldValues.field.translations',
            'prices',
            'accessories.accessory.translations',
            'colors.translations',
        ]);

        return new self(
            attributes: [
                'id' => (int) $product->getKey(),
                'family_id' => (int) $product->family_id,
                'data_template_id' => (int) $product->data_template_id,
                'code' => $product->code,
                'stock' => $product->stock,
                'disclaimer' => $product->disclaimer,
                'name' => $product->name,
                'created_at' => $product->created_at?->toISOString(),
                'updated_at' => $product->updated_at?->toISOString(),
            ],
            translations: $product->translations
                ->mapWithKeys(static fn ($translation) => [
                    $translation->locale => [
                        'name' => $translation->name,
                        'description' => $translation->description,
                    ],
                ])->toArray(),
            values: $product->fieldValues
                ->sortBy(static fn ($value) => $value->field?->position ?? 0)
                ->map(static fn ($value) => ProductValueData::fromModel($value))
                ->values()
                ->all(),
            prices: $product->prices
                ->sortBy('from')
                ->map(static fn ($price) => ProductPriceData::fromModel($price))
                ->values()
                ->all(),
            accessories: ProductAccessoryData::grouped($product->accessories),
            colors: $product->colors
                ->map(static function ($color) {
                    return [
                        'id' => (int) $color->getKey(),
                        'hex_code' => $color->hex_code,
                        'translations' => $color->translations
                            ->mapWithKeys(static fn ($translation) => [
                                $translation->locale => [
                                    'name' => $translation->name,
                                ],
                            ])->toArray(),
                    ];
                })
                ->values()
                ->all(),
            media: [
                'gallery' => self::serializeMedia($product, 'gallery'),
                'documents' => self::serializeMedia($product, 'documents'),
                'consultant_approvals' => self::serializeMedia($product, 'consultant_approvals'),
            ],
        );
    }

    /**
     * @param  Collection<int, Product>  $products
     * @return Collection<int, self>
     */
    public static function collection(Collection $products): Collection
    {
        return $products->map(static fn (Product $product) => self::fromModel($product));
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    private static function serializeMedia(Product $product, string $collection): array
    {
        return $product->getMedia($collection)
            ->map(static fn ($media) => [
                'id' => (int) $media->id,
                'name' => $media->name,
                'file_name' => $media->file_name,
                'mime_type' => $media->mime_type,
                'url' => $media->getUrl(),
            ])
            ->values()
            ->all();
    }
}
