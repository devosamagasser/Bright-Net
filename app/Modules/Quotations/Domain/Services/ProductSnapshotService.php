<?php

namespace App\Modules\Quotations\Domain\Services;

use Illuminate\Support\Facades\DB;
use App\Modules\Products\Domain\Models\Product;

class ProductSnapshotService
{
    /**
     * Build full snapshot (product + roots + optional price snapshot is handled separately).
     *
     * @return array{product_snapshot: array, roots_snapshot: array, roots: array}
     */
    public function buildSnapshots(Product $product): array
    {
        $roots = $this->resolveRoots($product);

        // return $roots
        return [
            'product_snapshot' => $this->makeProductSnapshot($product),
            'roots_snapshot' => $this->makeRootsSnapshot($roots),
            'roots' => $roots,
        ];
    }

    /**
     * Resolve solution / department / subcategory / family / supplier / brand roots.
     *
     * @return array{
     *     solution_id: int|null,
     *     solution_name: string|null,
     *     department_id: int|null,
     *     department_name: string|null,
     *     subcategory_id: int|null,
     *     subcategory_name: string|null,
     *     family_id: int|null,
     *     family_name: string|null,
     *     supplier_id: int|null,
     *     supplier_name: string|null,
     *     brand_id: int|null,
     *     brand_name: string|null
     * }
     */
    public function resolveRoots(Product $product): array
    {

        return [
            'solution_id' => $product->solution_id,
            'department_id' => $product->department_id,
            'subcategory_id' => $product->subcategory_id,
            'family_id' => $product->family_id,
            'supplier_id' => $product->supplier_id,
            'brand_id' => $product->brand->id ,
            'brand_name' => $product->brand->name,
        ];
    }

    /**
     * Build roots snapshot array from resolved roots.
     *
     * @param  array{
     *     solution_id: int|null,
     *     solution_name: string|null,
     *     department_id: int|null,
     *     department_name: string|null,
     *     subcategory_id: int|null,
     *     subcategory_name: string|null,
     *     family_id: int|null,
     *     family_name: string|null,
     *     supplier_id: int|null,
     *     supplier_name: string|null,
     *     brand_id: int|null,
     *     brand_name: string|null
     * } $roots
     */
    public function makeRootsSnapshot(array $roots): array
    {
        return [
            'solution' => [
                'id' => $roots['solution_id'],
                'name' => $roots['solution_name'],
            ],
            'department' => [
                'id' => $roots['department_id'],
                'name' => $roots['department_name'],
            ],
            'subcategory' => [
                'id' => $roots['subcategory_id'],
                'name' => $roots['subcategory_name'],
            ],
            'family' => [
                'id' => $roots['family_id'],
                'name' => $roots['family_name'],
            ],
            'brand' => [
                'id' => $roots['brand_id'],
                'name' => $roots['brand_name'],
            ],
            'supplier' => [
                'id' => $roots['supplier_id'],
                'name' => $roots['supplier_name'],
            ],
        ];
    }

    /**
     * Build product snapshot from Product model.
     */
    public function makeProductSnapshot(Product $product): array
    {
        return [
            'id' => (int) $product->getKey(),
            'code' => $product->code,
            'name' => $product->name,
            'description' => $product->description,
            'stock' => $product->stock,
            'disclaimer' => $product->disclaimer,
            'color' => $product->color,
            'style' => $product->style,
            'manufacturer' => $product->manufacturer,
            'application' => $product->application,
            'origin' => $product->origin,
        ];
    }
}


