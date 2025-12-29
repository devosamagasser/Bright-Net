<?php

namespace App\Modules\Products\Application\DTOs;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use App\Modules\Families\Domain\Models\Family;
use App\Modules\Products\Domain\Models\Product;

class ProductComparedData
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
        public readonly array $values,
        public readonly array $accessories,
        public readonly array $media,
    ) {
    }

    public static function collection(Product $firstProduct, Product $secondProduct): self
    {
        return new self(
            roots: self::serializeRoots([$firstProduct->family, $secondProduct->family]),
            attributes: self::serializeAttributes($firstProduct, $secondProduct),
            values: self::serializeValues($firstProduct, $secondProduct),
            accessories: self::serializeAccessories($firstProduct, $secondProduct),
            media: self::serializeMedia($firstProduct, $secondProduct)
        );
    }

    public static function serializeAttributes(Product $firstProduct, Product $secondProduct): array
    {
        return [
            [
                'key' => 'id',
                'FP_value' => (int) $firstProduct->getKey(),
                'SP_value' => (int) $secondProduct->getKey(),
            ],
            [
                'key' => 'code',
                'FP_value' => $firstProduct->code,
                'SP_value' => $secondProduct->code,
            ],
            [
                'key' => 'stock',
                'FP_value' => $firstProduct->stock,
                'SP_value' => $secondProduct->stock,
            ],
            [
                'key' => 'name',
                'FP_value' => $firstProduct->name,
                'SP_value' => $secondProduct->name,
            ],
            [
                'key' => 'description',
                'FP_value' => $firstProduct->description,
                'SP_value' => $secondProduct->description,
            ],
            [
                'key' => 'style',
                'FP_value' => $firstProduct->style,
                'SP_value' => $secondProduct->style,
            ],
            [
                'key' => 'manufacturer',
                'FP_value' => $firstProduct->manufacturer,
                'SP_value' => $secondProduct->manufacturer,
            ],
            [
                'key' => 'origin',
                'FP_value' => $firstProduct->origin,
                'SP_value' => $secondProduct->origin,
            ]
        ];
    }

    public static function serializeValues(Product $firstProduct, Product $secondProduct): array
    {
        $allFields = $firstProduct->fieldValues
            ->pluck('field')
            ->merge($secondProduct->fieldValues->pluck('field'))
            ->unique('id')
            ->sortBy('position');

        $serializedValues = [];

        foreach ($allFields as $field) {
            $firstValue = $firstProduct->fieldValues->firstWhere('field_id', $field->id);
            $secondValue = $secondProduct->fieldValues->firstWhere('field_id', $field->id);

            $serializedValues[] = [
                'field_key' => $field->key,
                'field_type' => $field->type,
                'FP_value' => $firstValue ? ProductValueData::serializeValue($firstValue, $field) : null,
                'SP_value' => $secondValue ? ProductValueData::serializeValue($secondValue, $field) : null,
            ];
        }

        return $serializedValues;
    }

    public static function serializeMedia(Product $firstProduct, Product $secondProduct): array
    {
        return [
            'FP_gallery' => $firstProduct->media
                ->where('collection_name', 'gallery')
                ->map(fn ($media) => [
                    'id' => (int) $media->id,
                    'name' => $media->name,
                    'file_name' => $media->file_name,
                    'mime_type' => $media->mime_type,
                    'url' => $media->getUrl(),
                ])->values()->all(),
            'SP_gallery' => $secondProduct->media
                ->where('collection_name', 'gallery')
                ->map(fn ($media) => [
                    'id' => (int) $media->id,
                    'name' => $media->name,
                    'file_name' => $media->file_name,
                    'mime_type' => $media->mime_type,
                    'url' => $media->getUrl(),
                ])->values()->all(),
        ];
    }

    public static function serializeAccessories(Product $firstProduct, Product $secondProduct): array
    {
        return [
            'FP_accessories' => ProductAccessoryData::grouped($firstProduct->accessories),
            'SP_accessories' => ProductAccessoryData::grouped($secondProduct->accessories),
        ];
    }

    public static function serializeRoots(array $families): array
    {
        $data = [
            'subcategory' => [],
            'originalDepartment' => [],
            'supplierSnapshotDepartment' => [],
            'supplierSnapshotBrand' => []
        ];

        foreach ($families as $key => $family) {
            $data['subcategory'][$key] = $family->subcategory;
            $data['originalDepartment'][$key] = $data['subcategory'][$key]->department;
            $data['supplierSnapshotDepartment'][$key] = $family->department;
            $data['supplierSnapshotBrand'][$key] = $data['supplierSnapshotDepartment'][$key]->supplierBrand;
        }

        return [
            [
                'key' => 'solution',
                'FP_solution_name' => $data['originalDepartment'][0]->solution->name ?? null,
                'SP_solution_name' => $data['originalDepartment'][1]->solution->name ?? null
            ],
            [
                'key' => 'brand',
                'FP_brand_name' => $data['supplierSnapshotBrand'][0]->brand->name ?? null,
                'FP_brand_id' => $data['supplierSnapshotBrand'][0]->brand->id ?? null,
                'SP_brand_name' => $data['supplierSnapshotBrand'][1]->brand->name ?? null,
                'SP_brand_id' => $data['supplierSnapshotBrand'][1]->brand->id ?? null,
            ],
            [
                'key' => 'department',
                'FP_department_name' => $data['originalDepartment'][0]->name ?? null,
                'FP_department_id' => $data['supplierSnapshotDepartment'][0]->id ?? null,
                'SP_department_name' => $data['originalDepartment'][1]->name ?? null,
                'SP_department_id' => $data['supplierSnapshotDepartment'][1]->id ?? null,
            ],
            [
                'key' => 'subcategory',
                'FP_subcategory_name' => $data['subcategory'][0]->name,
                'FP_subcategory_id' => $data['subcategory'][0]->id,
                'SP_subcategory_name' => $data['subcategory'][1]->name,
                'SP_subcategory_id' => $data['subcategory'][1]->id,
            ],
            [
                'key' => 'family',
                'FP_family_name' => $families[0]->name,
                'FP_family_id' => $families[0]->id,
                'SP_family_name' => $families[1]->name,
                'SP_family_id' => $families[1]->id,
            ]
        ];
    }
}
