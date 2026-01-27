<?php

namespace App\Modules\Products\Infrastructure\Persistence\Eloquent;

use Illuminate\Support\Arr;
use Illuminate\Http\Response;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use App\Modules\Shared\Support\Traits\HandleMedia;
use App\Modules\Shared\Support\Traits\HandlesTranslations;
use App\Modules\Products\Domain\ValueObjects\AccessoryType;
use App\Modules\Products\Domain\Models\{Product, ProductAccessory};
use App\Modules\Products\Domain\Repositories\ProductRepositoryInterface;

class EloquentProductRepository implements ProductRepositoryInterface
{
    use HandlesTranslations, HandleMedia;

    public function paginateAll(int $supplierId, int $perPage, array $filters, string $currency = 'USD' ): LengthAwarePaginator
    {
        return $this->getAllQuery($filters)
            ->where('supplier_id', $supplierId)
            ->paginate($perPage);
    }

    public function paginateByProductIds(array $productsIds, int $perPage, array $filters, string $currency = 'USD' ): LengthAwarePaginator
    {
        return $this->getAllQuery($filters)
            ->whereIn('id', $productsIds)
            ->paginate($perPage);
    }
    public function create(array $attributes, array $translations, array $media): Product
    {
        return $this->fillProduct(
            new Product(),
            $attributes,
            $translations,
            $media,
        );
    }

    public function update(Product $product, array $attributes, array $translations, array $media): Product
    {
        return $this->fillProduct(
            $product,
            $attributes,
            $translations,
            $media,
            true
        );
    }

    public function delete(Product $product): void
    {
        DB::transaction(static function () use ($product): void {
            $product->delete();
        });
    }

    public function find(int $id,array $attributes = ["*"], array $relations = []): ?Product
    {
        return Product::query()
            ->select($attributes)
            ->with($relations ?: $this->allRelations())
            ->findOrFail($id);
    }

    public function findWhere(array $array, array $relations = []): ?Product
    {
        return Product::query()
            ->with($relations ?: $this->allRelations())
            ->where($array)
            ->firstOrFail();
    }

    public function paginateByGroup(int $groupId, int $perPage = 15, ?int $supplierId = null): LengthAwarePaginator
    {
        return Product::query()
            ->with([
                'supplier',
                'media',
                'fieldValues.field',
                'family',
                'solution',
                'subcategory',
                'department',
                'brand',
            ])
            ->where('product_group_id', $groupId)
            ->when($supplierId !== null, static function ($query) use ($supplierId): void {
                $query->whereHas('family', static function ($familyQuery) use ($supplierId): void {
                    $familyQuery->where('supplier_id', $supplierId);
                });
            })
            ->orderBy('code')
            ->paginate($perPage);
    }

    public function getByGroups(array $groupIds, ?int $supplierId = null): Collection
    {
        if (empty($groupIds)) {
            return collect();
        }

        $query = Product::query()
            ->select('products.*')
            ->selectRaw('ROW_NUMBER() OVER (PARTITION BY product_group_id ORDER BY id) as rn')
            ->whereIn('product_group_id', $groupIds)
            ->where('supplier_id', $supplierId);

        return Product::query()
            ->fromSub($query, 'p')
            ->where('rn', 1)
            ->with([
                'supplier',
                'media',
                'fieldValues.field.translations',
                'translations',
                'family'
            ])
            ->orderBy('product_group_id')
            ->get();
    }


    public function cutPasteProduct(Product $product, int $family_id): Product
    {
        return DB::transaction(function () use ($product, $family_id): Product {
            $product->family_id = $family_id;
            $product->save();

            return $this->loadAggregates($product);
        });
    }

    public function compare(array $productIds): Collection
    {
        // Limit to maximum 3 products
        $productIds = array_slice($productIds, 0, 3);
        $productIds = array_unique($productIds);

        if (count($productIds) < 2) {
            throw new \InvalidArgumentException('At least 2 products are required for comparison.', Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        // Optimized relations - only load what's needed for comparison
        $products = Product::query()
            ->whereIn('id', $productIds)
            ->with($this->compareRelations())
            ->get();

        if ($products->count() !== count($productIds)) {
            throw new \InvalidArgumentException('One or more products not found.', Response::HTTP_NOT_FOUND);
        }

        // Validate all products belong to the same subcategory
        $subcategoryIds = $products->pluck('family.subcategory_id')->unique();
        if ($subcategoryIds->count() > 1) {
            throw new \InvalidArgumentException('Products must belong to the same subcategory for comparison.', Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        return $products;
    }

    /**
     * Optimized relations for comparison - only load what's needed
     */

    public function attachAccessory( Product $product, Product $accessory, AccessoryType $type, ?int $quantity = null): ProductAccessory {
        return DB::transaction(function () use ($product, $accessory, $type, $quantity): ProductAccessory {
            /** @var ProductAccessory $record */
            $record = $product->accessories()->updateOrCreate(
                [
                    'accessory_id' => $accessory->getKey(),
                ],
                [
                    'accessory_type' => $type,
                    'quantity' => $quantity !== null ? (string) $quantity : null,
                ]
            );

            return $record->load('accessory.translations');
        });
    }

    private function loadAggregates(Product $product): Product
    {
        return $product->load($this->allRelations());
    }

    protected function fillProduct(Product $product, array $attributes, array $translations, array $media, bool $isUpdate = false): Product
    {
        $product->fill($attributes);
        $this->fillTranslations($product, $translations);
        $product->save();
        $this->syncMedia($product, $media, $isUpdate);
        return $product;
    }
    private function getAllQuery($filters)
    {
        return Product::filter($filters)->with([
            'supplier',
            'media',
            'fieldValues.field',
            'family',
            'subcategory',
            'department',
            'brand',
            'solution',
            'prices'
        ]);
    }
    private function compareRelations(): array
    {
        return [
            'supplier',
            'media',
            'translations',
            'fieldValues.field.translations',
            'family.translations',
            'family.subcategory.translations',
            'family.subcategory.department.solution.translations',
            'family.department.supplierBrand.brand',
            'family.department.department.translations',
            'accessories.accessory.translations',
            'accessories.accessory.media',
            'accessories.accessory.fieldValues.field.translations',
            'accessories.accessory.family.translations',
            'accessories.accessory.family.subcategory.translations',
        ];
    }
    private function allRelations()
    {
        return [
            'supplier',
            'media',
            'translations',
            'fieldValues.field.translations',
            'prices',
            'family.translations',
            'family.supplier',
            'family.subcategory.department.solution.translations',
            'family.department.supplierBrand.brand',
            'family.department.department.translations',
            'family.subcategory.translations',
            'accessories.accessory.translations',
            'accessories.accessory.media',
            'accessories.accessory.fieldValues.field.translations',
            'accessories.accessory.family.translations',
            'accessories.accessory.family.supplier',
            'accessories.accessory.family.subcategory.department.solution.translations',
            'accessories.accessory.family.department.supplierBrand.brand',
            'accessories.accessory.family.department.department.translations',
            'accessories.accessory.family.subcategory.translations',
        ];
    }
}
