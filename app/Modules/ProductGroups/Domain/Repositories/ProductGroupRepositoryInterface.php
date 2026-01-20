<?php

namespace App\Modules\Products\Domain\Repositories;

use Illuminate\Support\Collection;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use App\Modules\Products\Domain\Models\{ProductAccessory, ProductGroup};

interface ProductGroupRepositoryInterface
{
    /**
     * @param  int|null  $groupId
     * @param  array<string, mixed>  $attributes
     */
    public function firstOrcreate(?int $groupId, array $attributes): ProductGroup;

    public function delete(ProductGroup $productGroup): void;

    public function find(int $id, array $atttibutes = [], array $relations = []): ?ProductGroup;

    /**
     * @return Collection<int, ProductGroup>
     */
    public function getByFamily(int $familyId, ?int $supplierId = null): Collection;

    /**
     * @return LengthAwarePaginator
     */
    public function paginateByFamily(int $familyId, int $perPage = 15, ?int $supplierId = null): LengthAwarePaginator;

    public function cutPasteProduct(ProductGroup $productGroup, int $family_id): ProductGroup;
}
