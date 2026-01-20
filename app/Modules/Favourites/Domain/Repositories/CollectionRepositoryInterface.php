<?php

namespace App\Modules\Favourites\Domain\Repositories;

use App\Modules\Favourites\Domain\Models\FavouriteCollection;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

interface CollectionRepositoryInterface
{
    /**
     * Paginate collections for a company.
     */
    public function getByCompany(int $companyId, $perPage): LengthAwarePaginator;

    /**
     * Retrieve a collection by its primary key.
     */
    public function find(int $id, $perPage, $filter = []): array;

    /**
     * Create a new collection.
     *
     * @param array<string, mixed> $attributes
     */
    public function create(array $attributes): FavouriteCollection;

    /**
     * Update an existing collection.
     *
     * @param array<string, mixed> $attributes
     */
    public function update(FavouriteCollection $collection, array $attributes): FavouriteCollection;

    /**
     * Delete a collection.
     */
    public function delete(FavouriteCollection $collection): void;

    /**
     * Add a product to a collection.
     */
    public function addProduct(FavouriteCollection $collection, int $productId): void;

    /**
     * Remove a product from a collection.
     */
    public function removeProduct(FavouriteCollection $collection, int $productId): void;

    /**
     * Check if a product exists in a collection.
     */
    public function hasProduct(FavouriteCollection $collection, int $productId): bool;

    public function getRelatedModelsForCollection(int $collectionId, string $relationName, int $perPage = 15);
}
