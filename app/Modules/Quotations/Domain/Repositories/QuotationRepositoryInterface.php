<?php

namespace App\Modules\Quotations\Domain\Repositories;

use App\Modules\Products\Domain\Models\Product;
use App\Modules\Quotations\Domain\Models\{
    Quotation,
    QuotationProduct,
    QuotationProductAccessory,
};

interface QuotationRepositoryInterface
{
    public function getOrCreateDraft(int $supplierId): Quotation;

    /**
     * @param  array<string, mixed>  $attributes
     */
    public function update(Quotation $quotation, array $attributes): Quotation;

    /**
     * @param  array<string, mixed>  $attributes
     * @param  array<int, array{product: Product, attributes: array<string, mixed>}>  $accessories
     */
    public function addProduct(Quotation $quotation, Product $product, array $attributes, array $accessories = []): QuotationProduct;

    /**
     * @param  array<string, mixed>  $attributes
     * @param  array<int, array{product: Product, attributes: array<string, mixed>}>  $accessories
     */
    public function replaceProduct(QuotationProduct $current, Product $replacement, array $attributes, array $accessories = []): QuotationProduct;

    /**
     * @param  array<string, mixed>  $attributes
     */
    public function updateProduct(QuotationProduct $item, array $attributes): QuotationProduct;

    public function deleteProduct(QuotationProduct $item): void;

    /**
     * @param  array<string, mixed>  $attributes
     */
    public function addAccessory(QuotationProduct $item, Product $accessory, array $attributes): QuotationProductAccessory;

    /**
     * @param  array<string, mixed>  $attributes
     */
    public function updateAccessory(QuotationProductAccessory $accessory, array $attributes): QuotationProductAccessory;

    public function deleteAccessory(QuotationProductAccessory $accessory): void;

    public function refreshTotals(Quotation $quotation): Quotation;

    public function loadRelations(Quotation $quotation): Quotation;
}
