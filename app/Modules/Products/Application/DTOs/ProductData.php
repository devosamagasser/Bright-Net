<?php

namespace App\Modules\Products\Application\DTOs;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use App\Modules\Families\Domain\Models\Family;
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
        // public readonly array $colors,
        public readonly array $media,
    ) {
    }

    public static function fromModel(Product $product)
    {
        $productData = new self(
            attributes: [
                'id' => (int) $product->getKey(),
                'family_id' => (int) $product->family_id,
                'data_template_id' => (int) $product->data_template_id,
                'code' => $product->code,
                'stock' => $product->stock,
                'disclaimer' => $product->disclaimer,
                'name' => $product->name,
                'description' => $product->description,
                'color' => $product->color,
                'style' => $product->style,
                'manufacturer' => $product->manufacturer,
                'application' => $product->application,
                'origin' => $product->origin,
                'created_at' => $product->created_at?->toISOString(),
                'updated_at' => $product->updated_at?->toISOString(),
            ],
            translations: $product->relationLoaded('translations') ? $product->translations
                ->mapWithKeys(static fn ($translation) => [
                    $translation->locale => [
                        'name' => $translation->name,
                        'description' => $translation->description,
                    ],
                ])->toArray() : [],
            values: $product->fieldValues
                ->sortBy(static fn ($value) => $value->field?->position ?? 0)
                ->map(static fn ($value) => ProductValueData::fromModel($value))
                ->values()
                ->all(),
            prices: $product->relationLoaded('prices') ? $product->prices
                ->sortBy('from')
                ->map(static fn ($price) => ProductPriceData::fromModel($price))
                ->values()
                ->all() : [],
            accessories: $product->relationLoaded('accessories') ? ProductAccessoryData::grouped($product->accessories) : [],
            media: [
                'quotation_image' => self::serializeMedia($product, 'quotation_image'),
                'gallery' => self::serializeMedia($product, 'gallery'),
                'documents' => self::serializeMedia($product, 'documents'),
                'dimensions' => self::serializeMedia($product, 'dimensions'),
                'consultant_approvals' => self::serializeMedia($product, 'consultant_approvals'),
            ],
        );

        return collect([
            'products' => $productData,
        ]);
    }

    /**
     * @param  Collection<int, Product>  $products
     * @return Collection<int, self>
     */
    public static function collection(Collection $products, ?Family $family = null ): Collection
    {
        return $products->map(fn(Product $product) => self::fromModel($product));
    }

    public static function serializeMedia(Product $product, string $collection): array
    {
        return $product->media
            ->where('collection_name', $collection)
            ->map(fn ($media) => [
                'id' => (int) $media->id,
                'name' => $media->name,
                'file_name' => $media->file_name,
                'mime_type' => $media->mime_type,
                'url' => $media->getUrl(),
            ])
            ->values()
            ->all();
    }

    public static function serializeRoots(Family $family): array
    {
        return [
            'solution' => [
                'name' => $family->subcategory->department->solution->name ?? null,
            ],
            'brand' => [
                'name' => $family->department->supplierBrand->brand->name ?? null,
            ],
            'department' => [
                'name' => $family->subcategory->department->name ?? null,
            ],
            'subcategory' => [
                'name' => $family->subcategory->name,
                'id' => $family->subcategory_id,
            ],
            'family' => [
                'name' => $family->name,
                'id' => $family->id,
            ],
        ];
    }
}
