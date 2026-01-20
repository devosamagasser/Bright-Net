<?php

namespace App\Modules\Products\Application\DTOs;

use Illuminate\Support\Collection;
use App\Modules\Products\Domain\Models\Product;
use App\Modules\Products\Domain\Models\ProductGroup;
use App\Modules\Products\Application\DTOs\ProductValueData;

class ProductGroupData
{
    /**
     * @param  array<string, mixed>  $attributes
     * @param  array<string, mixed>|null  $firstProduct
     */
    private function __construct(
        public readonly array $attributes,
        public readonly ?array $firstProduct,
    ) {
    }

    public static function fromModel(ProductGroup $group, ?Product $firstProduct = null): self
    {
        $firstProductData = null;
        if ($firstProduct !== null) {
            $productData = ProductData::fromModel($firstProduct);
            $firstProductData = [
                'id' => $productData->attributes['id'],
                'data_template_id' => $productData->attributes['data_template_id'],
                'code' => $productData->attributes['code'],
                'stock' => $productData->attributes['stock'],
                'disclaimer' => $productData->attributes['disclaimer'],
                'name' => $productData->attributes['name'],
                'description' => $productData->attributes['description'],
                'color' => $productData->attributes['color'] ?? null,
                'style' => $productData->attributes['style'] ?? null,
                'manufacturer' => $productData->attributes['manufacturer'] ?? null,
                'application' => $productData->attributes['application'] ?? null,
                'origin' => $productData->attributes['origin'] ?? null,
                'values' => array_values(array_filter(array_map(
                    static function (ProductValueData $value) {
                        return [
                            'field' => $value->field,
                            'value' => $value->value,
                        ];
                    },
                    $productData->values
                ))),
                'media' => $productData->media,
            ];
        }

        $groupData = new self(
            attributes: [
                'id' => (int) $group->getKey(),
                'family_id' => (int) $group->family_id,
                'data_template_id' => $group->data_template_id ? (int) $group->data_template_id : null,
                'supplier_id' => $group->supplier_id ? (int) $group->supplier_id : null,
                'solution_id' => $group->solution_id ? (int) $group->solution_id : null,
                'subcategory_id' => $group->subcategory_id ? (int) $group->subcategory_id : null,
                'created_at' => $group->created_at?->diffForHumans(),
                'updated_at' => $group->updated_at?->diffForHumans(),
            ],
            firstProduct: $firstProductData,
        );

        return $groupData;
    }

    /**
     * @param  Collection<int, ProductGroup>  $groups
     * @param  Collection<int, Product>  $firstProducts
     * @return Collection<int, self>
     */
    public static function collection(Collection $groups, Collection $firstProducts): Collection
    {
        $firstProductsMap = $firstProducts->keyBy('product_group_id');
        
        return $groups->map(function (ProductGroup $group) use ($firstProductsMap) {
            $firstProduct = $firstProductsMap->get($group->id);
            return self::fromModel($group, $firstProduct);
        });
    }
}

