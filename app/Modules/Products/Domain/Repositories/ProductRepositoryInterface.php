<?php

namespace App\Modules\Products\Domain\Repositories;

use Illuminate\Support\Collection;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use App\Modules\Products\Domain\Models\{Product, ProductAccessory};
use App\Modules\Products\Domain\ValueObjects\AccessoryType;

interface ProductRepositoryInterface
{
    /**
     * @param  array<string, mixed>  $attributes
     * @param  array<string, array<string, mixed>>  $translations
     * @param  array<string, mixed>  $values
     * @param  array<string, mixed>  $relations
     */
    public function create(array $attributes, array $translations, array $media): Product;

    /**
     * @param  array<string, mixed>  $attributes
     * @param  array<string, array<string, mixed>>  $translations
     * @param  array<string, mixed>  $values
     * @param  array<string, mixed>  $relations
     */
    public function update(Product $product, array $attributes, array $translations, array $media): Product;

    public function delete(Product $product): void;

    public function find(int $id, array $attributes = [], array $relations = []): ?Product;

    /**
     * @param  array<int>  $productIds
     * @return Collection<int, Product>
     */
    public function compare(array $productIds): Collection;

    /**
     * @return LengthAwarePaginator
     */
    public function paginateByGroup(int $groupId, int $perPage = 15, ?int $supplierId = null): LengthAwarePaginator;

    /**
     * Get first product for each group
     * @param  array<int>  $groupIds
     * @param  int|null  $supplierId
     * @return Collection<int, Product>
     */
    public function getByGroups(array $groupIds, ?int $supplierId = null): Collection;

    public function attachAccessory(Product $product, Product $accessory, AccessoryType $type, ?int $quantity = null): ProductAccessory;

    public function cutPasteProduct(Product $product, int $family_id): Product;

    public function paginateAll(int $supplierId, int $perPage, array $filters, string $currency = 'USD'): LengthAwarePaginator;

    public function paginateByProductIds(array $productsIds, int $perPage, array $filters, string $currency = 'USD'): LengthAwarePaginator;

    public function findWhere(array $array, array $relations);
}
