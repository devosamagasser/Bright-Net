<?php

namespace App\Modules\Products\Infrastructure\Persistence\Eloquent;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use App\Modules\Products\Domain\Models\{Product, ProductGroup};
use App\Modules\Products\Domain\Repositories\ProductGroupRepositoryInterface;

class ProductGroupRepository implements ProductGroupRepositoryInterface
{

    public function firstOrcreate(?int $groupId, array $attributes): ProductGroup
    {
        if ($groupId !== null) {
            $group = ProductGroup::query()->find($groupId);
            if ($group !== null) {
                return $group;
            }
        }

        // Create new group with attributes from product
        $groupAttributes = [
            'family_id' => $attributes['family_id'],
            'supplier_id' => $attributes['supplier_id'] ?? null,
            'solution_id' => $attributes['solution_id'] ?? null,
            'supplier_solution_id' => $attributes['supplier_solution_id'] ?? null,
            'brand_id' => $attributes['brand_id'] ?? null,
            'supplier_brand_id' => $attributes['supplier_brand_id'] ?? null,
            'department_id' => $attributes['department_id'] ?? null,
            'supplier_department_id' => $attributes['supplier_department_id'] ?? null,
            'subcategory_id' => $attributes['subcategory_id'] ?? null,
            'data_template_id' => $attributes['data_template_id'] ?? null,
        ];

        return ProductGroup::query()->create($groupAttributes);
    }

    public function delete(ProductGroup $productGroup): void
    {
        DB::transaction(static function () use ($productGroup): void {
            $productGroup->delete();
        });
    }

    public function find(int $id, array $atttibutes = ["*"], array $relations = []): ?ProductGroup
    {
        return ProductGroup::query()
            ->select($atttibutes)
            ->with($relations ?: $this->allRelations())
            ->find($id);
    }

    public function getByFamily(int $familyId, ?int $supplierId = null): Collection
    {
        return ProductGroup::query()
            ->with($this->allRelations())
            ->where('family_id', $familyId)
            ->when($supplierId !== null, static function ($query) use ($supplierId): void {
                $query->where('supplier_id', $supplierId);
            })
            ->orderBy('id')
            ->get();
    }

    public function paginateByFamily(int $familyId, int $perPage = 15, ?int $supplierId = null): LengthAwarePaginator
    {
        return ProductGroup::query()
            ->with($this->allRelations())
            ->where('family_id', $familyId)
            ->when($supplierId !== null, static function ($query) use ($supplierId): void {
                $query->where('supplier_id', $supplierId);
            })
            ->orderBy('id')
            ->paginate($perPage);
    }

    public function cutPasteProduct(ProductGroup $productGroup, int $family_id): ProductGroup
    {
        return DB::transaction(function () use ($productGroup, $family_id): ProductGroup {
            $productGroup->family_id = $family_id;
            $productGroup->save();

            return $this->loadAggregates($productGroup);
        });
    }

    private function loadAggregates(ProductGroup $productGroup): ProductGroup
    {
        return $productGroup->load($this->allRelations());
    }

    private function allRelations(): array
    {
        return [
            'products.media',
            'products.translations',
            'products.fieldValues.field.translations',
            'family.translations',
            'family.subcategory.department.solution.translations',
            'family.department.supplierBrand.brand',
            'family.department.department.translations',
            'family.subcategory.translations',
        ];
    }
}
