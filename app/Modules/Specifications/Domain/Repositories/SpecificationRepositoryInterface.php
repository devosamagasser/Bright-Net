<?php

namespace App\Modules\Specifications\Domain\Repositories;

use Illuminate\Database\Eloquent\Model;
use App\Modules\Products\Domain\Models\Product;
use App\Modules\Specifications\Domain\Models\{
    Specification,
    SpecificationItem,
    SpecificationItemAccessory
};

interface SpecificationRepositoryInterface
{
    public function getOrCreateDraft(Model $user): Specification;

    /**
     * @param  array<string, mixed>  $attributes
     */
    public function update(Specification $specification, array $attributes): Specification;

    /**
     * @param  array<string, mixed>  $attributes
     * @param  array<int, array{product: Product, attributes: array<string, mixed>}>  $accessories
     */
    public function addItem(Specification $specification, Product $product, array $attributes, array $accessories = []): SpecificationItem;

    /**
     * @param  array<string, mixed>  $attributes
     * @param  array<int, array{product: Product, attributes: array<string, mixed>}>  $accessories
     */
    public function replaceItem(SpecificationItem $current, Product $replacement, array $attributes, array $accessories = []): SpecificationItem;

    /**
     * @param  array<string, mixed>  $attributes
     */
    public function updateItem(SpecificationItem $item, array $attributes): SpecificationItem;

    public function deleteItem(SpecificationItem $item): void;

    /**
     * @param  array<string, mixed>  $attributes
     */
    public function addAccessory(SpecificationItem $item, Product $accessory, array $attributes): SpecificationItemAccessory;

    /**
     * @param  array<string, mixed>  $attributes
     */
    public function updateAccessory(SpecificationItemAccessory $accessory, array $attributes): SpecificationItemAccessory;

    public function deleteAccessory(SpecificationItemAccessory $accessory): void;

    public function refreshTotals(Specification $specification): Specification;

    public function loadRelations(Specification $specification): Specification;
}


