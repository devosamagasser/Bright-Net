<?php

namespace App\Modules\Products\Application\DTOs;

use Illuminate\Support\Collection;
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

    /**
     * @param  Collection<int, Product>  $products
     */
    public static function collection(Collection $products): self
    {
        $productsArray = $products->values()->all();
        
        return new self(
            roots: self::serializeRoots($products->pluck('family')->all()),
            attributes: self::serializeAttributes($productsArray),
            values: self::serializeValues($productsArray),
            accessories: self::serializeAccessories($productsArray),
            media: self::serializeMedia($productsArray)
        );
    }

    /**
     * @param  array<int, Product>  $products
     */
    public static function serializeAttributes(array $products): array
    {
        $attributes = ['id', 'code', 'stock', 'name', 'description', 'style', 'manufacturer', 'origin'];
        $result = [];

        foreach ($attributes as $attr) {
            $row = ['key' => $attr];
            foreach ($products as $index => $product) {
                $row["P" . ($index + 1) . "_value"] = match($attr) {
                    'id' => (int) $product->getKey(),
                    default => $product->$attr,
                };
            }
            $result[] = $row;
        }

        return $result;
    }

    /**
     * @param  array<int, Product>  $products
     */
    public static function serializeValues(array $products): array
    {
        // Collect all unique fields from all products
        $allFields = collect();
        foreach ($products as $product) {
            $allFields = $allFields->merge($product->fieldValues->pluck('field'));
        }
        $allFields = $allFields->unique('id')->sortBy('position');

        $serializedValues = [];

        foreach ($allFields as $field) {
            $row = [
                'field_key' => $field->key,
                'field_type' => $field->type,
            ];

            foreach ($products as $index => $product) {
                $value = $product->fieldValues->firstWhere('field_id', $field->id);
                $row["P" . ($index + 1) . "_value"] = $value 
                    ? ProductValueData::serializeValue($value, $field) 
                    : null;
            }

            $serializedValues[] = $row;
        }

        return $serializedValues;
    }

    /**
     * @param  array<int, Product>  $products
     */
    public static function serializeMedia(array $products): array
    {
        $result = [];
        
        foreach ($products as $index => $product) {
            $result["P" . ($index + 1) . "_gallery"] = $product->media
                ->where('collection_name', 'gallery')
                ->map(fn ($media) => [
                    'id' => (int) $media->id,
                    'name' => $media->name,
                    'file_name' => $media->file_name,
                    'mime_type' => $media->mime_type,
                    'url' => $media->getUrl(),
                ])->values()->all();
        }

        return $result;
    }

    /**
     * @param  array<int, Product>  $products
     */
    public static function serializeAccessories(array $products): array
    {
        $result = [];
        
        foreach ($products as $index => $product) {
            $result["P" . ($index + 1) . "_accessories"] = ProductAccessoryData::grouped($product->accessories);
        }

        return $result;
    }

    /**
     * @param  array<int, Family>  $families
     */
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

        $rootKeys = ['solution', 'brand', 'department', 'subcategory', 'family'];
        $result = [];

        foreach ($rootKeys as $rootKey) {
            $row = ['key' => $rootKey];
            
            foreach ($families as $index => $family) {
                $prefix = "P" . ($index + 1) . "_";
                
                switch ($rootKey) {
                    case 'solution':
                        $row[$prefix . 'solution_name'] = $data['originalDepartment'][$index]->solution->name ?? null;
                        break;
                    case 'brand':
                        $row[$prefix . 'brand_name'] = $data['supplierSnapshotBrand'][$index]->brand->name ?? null;
                        $row[$prefix . 'brand_id'] = $data['supplierSnapshotBrand'][$index]->brand->id ?? null;
                        break;
                    case 'department':
                        $row[$prefix . 'department_name'] = $data['originalDepartment'][$index]->name ?? null;
                        $row[$prefix . 'department_id'] = $data['supplierSnapshotDepartment'][$index]->id ?? null;
                        break;
                    case 'subcategory':
                        $row[$prefix . 'subcategory_name'] = $data['subcategory'][$index]->name;
                        $row[$prefix . 'subcategory_id'] = $data['subcategory'][$index]->id;
                        break;
                    case 'family':
                        $row[$prefix . 'family_name'] = $families[$index]->name;
                        $row[$prefix . 'family_id'] = $families[$index]->id;
                        break;
                }
            }
            
            $result[] = $row;
        }

        return $result;
    }
}
