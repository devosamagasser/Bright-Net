<?php

namespace App\Modules\Favourites\Application\DTOs;

use App\Modules\Favourites\Domain\Models\FavouriteCollection;
use App\Modules\Products\Application\DTOs\ProductValueData;
use App\Modules\Products\Domain\Models\Product;
use Illuminate\Support\Collection;

class ProductData
{
    /**
     * @param array<int, array<string, mixed>> $products
     */
    private function __construct(
        public readonly array $supplier,
        public readonly array $solution,
        public readonly array $brand,
        public readonly array $department,
        public readonly array $subcategory,
        public readonly array $family,
        public readonly string $code,
        public readonly int $stock,
        public readonly ?string $color = null,
        public readonly ?string $origin = null,
        public readonly array $values,
        public readonly array $media,
    ) {
    }

    public static function fromModel(Product $product): self
    {
        return new self(
            supplier: [
                'id' => $product->supplier_id,
                'supplier' => $product->supplier->company->name
            ],
            solution: [
                'id' => $product->solution_id,
                'solution' => $product->solution->name,
            ],
            brand: [
                'id' => $product->brand_id,
                'brand' => $product->brand->name
            ],
            department: [
                'id' => $product->department_id,
                'department' => $product->department->name
            ],
            subcategory: [
                'id' => $product->subcategory_id,
                'subcategory' => $product->subcategory->name
            ],
            family: [
                'id' => $product->family_id,
                'family' => $product->family->name
            ],
            code: $product->code ,
            stock: $product->stock ,
            color: $product->color ,
            origin: $product->origin ,
            values: $product->fieldValues
                ->sortBy(static fn ($value) => $value->field?->position ?? 0)
                ->map(static fn ($value) => ProductValueData::fromModel($value))
                ->values()
                ->all(),
            media: [
                'quotation_image' => \App\Modules\Products\Application\DTOs\ProductData::serializeMedia($product, 'quotation_image'),
                'gallery' => \App\Modules\Products\Application\DTOs\ProductData::serializeMedia($product, 'gallery'),
            ],
        );
    }

    /**
     * @param Collection<int, FavouriteCollection> $collections
     * @return Collection<int, self>
     */
    public static function collection(Collection $products): Collection
    {
        return $products->map(static fn (Product $product) => self::fromModel($product));
    }
}

