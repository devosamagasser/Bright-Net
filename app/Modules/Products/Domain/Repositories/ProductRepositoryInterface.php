<?php

namespace App\Modules\Products\Domain\Repositories;

use Illuminate\Support\Collection;
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
    public function create(array $attributes, array $translations, array $values, array $relations = []): Product;

    /**
     * @param  array<string, mixed>  $attributes
     * @param  array<string, array<string, mixed>>  $translations
     * @param  array<string, mixed>  $values
     * @param  array<string, mixed>  $relations
     */
    public function update(Product $product, array $attributes, array $translations, array $values, array $oldGallery = [], array $relations = []): Product;

    public function delete(Product $product): void;

    public function find(int $id): ?Product;

    /**
     * @return Collection<int, Product>
     */
    public function getByFamily(int $familyId, ?int $supplierId = null): Collection;

    public function attachAccessory(
        Product $product,
        Product $accessory,
        AccessoryType $type,
        ?int $quantity = null
    ): ProductAccessory;

    public function findAccessoryOfProduct(int $product, int $accessoryId): ?ProductAccessory;

    public function cutPasteProduct(Product $product, int $family_id): Product;
}
