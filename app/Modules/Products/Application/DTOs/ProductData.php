<?php

namespace App\Modules\Products\Application\DTOs;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use App\Modules\Families\Domain\Models\Family;
use App\Modules\Products\Domain\Models\Product;
use App\Modules\Products\Domain\Models\ProductGroup;

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
        public readonly array $roots,
        public readonly array $attributes,
        public readonly array $translations,
        public readonly array $values,
        public readonly array $prices,
        public readonly array $accessories,
        public readonly array $media,
    ) {
    }

    public static function fromModel(
        Product $product,
        $withRoots = false,
        ?string $targetCurrency = null,
        ?\DateTime $maxFactorCreatedAt = null
    ): self {
        $supplier = $product->relationLoaded('supplier') ? $product->supplier : null;
        if ($supplier === null && $product->supplier_id) {
            $supplier = $product->supplier;
        }

        return new self(
            roots: $withRoots ? self::serializeRoots($product) : [],
            attributes: [
                'id' => (int) $product->getKey(),
                'family_id' => (int) $product->family_id,
                'product_group_id' => $product->product_group_id ? (int) $product->product_group_id : null,
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
                'created_at' => $product->created_at?->diffForHumans(),
                'updated_at' => $product->updated_at?->diffForHumans(),
            ],
            translations: $product->whenRelationLoaded(
                relation: 'translations',
                callback: fn() => $product->translations
                    ->mapWithKeys(static fn ($translation) => [
                        $translation->locale => [
                            'name' => $translation->name,
                            'description' => $translation->description,
                        ],
                    ])->toArray(),
                default: []
            ),
            values: $product->whenRelationLoaded(
                relation: 'fieldValues',
                callback: fn() => $product->fieldValues
                    ->sortBy(static fn ($value) => $value->field?->position ?? 0)
                    ->map(static fn ($value) => ProductValueData::fromModel($value))
                    ->values()
                    ->all(),
                default: []
            ),
            prices: $product->whenRelationLoaded(
                relation: 'prices',
                callback: fn() => $product->prices
                    ->sortBy('from')
                    ->map(static fn ($price) => ProductPriceData::fromModel($price, $targetCurrency, $supplier, $maxFactorCreatedAt))
                    ->values()
                    ->all(),
                default: []
            ),
            accessories: $product->whenRelationLoaded(
                relation: 'accessories',
                callback: fn() => ProductAccessoryData::grouped($product->accessories),
                default: [],
            ),
            media: $product->whenRelationLoaded(
                relation: 'media',
                callback: fn() => [
                    'quotation_image' => self::serializeMedia($product, 'quotation_image'),
                    'gallery' => self::serializeMedia($product, 'gallery'),
                    'documents' => self::serializeMedia($product, 'documents'),
                    'dimensions' => self::serializeMedia($product, 'dimensions'),
                    'consultant_approvals' => self::serializeMedia($product, 'consultant_approvals'),
                ],
                default: [],
            )
        );
    }

    /**
     * @param  Collection<int, Product>  $products
     * @return Collection<int, self>
     */
    public static function collection(
        Collection $products,
        bool $withRoots = false,
        ?string $targetCurrency = null,
        ?\DateTime $maxFactorCreatedAt = null
    ): Collection {
        return $products->map(fn(Product $product) => self::fromModel(
            $product,
            withRoots: $withRoots,
            targetCurrency: $targetCurrency,
            maxFactorCreatedAt: $maxFactorCreatedAt
        ));
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

//    public static function serializeRoots(Family $family, ?ProductGroup $group = null): array
//    {
//        $subcategory = $family->subcategory;
//        $originalDepartment = $subcategory->department;
//        $supplierSnapshotDepartment = $family->department;
//        $supplierSnapshotBrand = $supplierSnapshotDepartment->supplierBrand;
//
//        $roots = [
//            'solution' => [
//                'name' => $originalDepartment->solution->name ?? null,
//                'id' => $originalDepartment->solution_id ?? null
//            ],
//            'brand' => [
//                'name' => $supplierSnapshotBrand->brand->name ?? null,
//                'id' => $supplierSnapshotBrand->id ?? null,
//            ],
//            'department' => [
//                'name' => $originalDepartment->name ?? null,
//                'id' => $supplierSnapshotDepartment->id ?? null,
//            ],
//            'subcategory' => [
//                'name' => $subcategory->name,
//                'id' => $subcategory->id,
//            ],
//            'family' => [
//                'name' => $family->name,
//                'id' => $family->id,
//            ],
//        ];
//
//        if ($group !== null) {
//            $roots['group'] = [
//                'id' => $group->id,
//            ];
//        }
//
//        return $roots;
//    }
    public static function serializeRoots(Product $product): array
    {
        return [
            'solution' => [
                'name' => $product->name ,
                'id' => $product->solution_id
            ],
            'brand' => [
                'name' => $product->brand->name ,
                'id' => $product->supplier_brand_id ,
            ],
            'department' => [
                'id' => $product->supplier_department_id ,
                'name' => $product->department->name ,
            ],
            'subcategory' => [
                'name' => $product->subcategory->name,
                'id' => $product->subcategory_id,
            ],
            'family' => [
                'name' => $product->family->name,
                'id' => $product->family_id,
            ],
            'group' => [
                'id' => $product->product_group_id,
            ]
        ];
    }
}
