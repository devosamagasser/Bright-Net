<?php

namespace App\Modules\Quotations\Application\UseCases;

use Illuminate\Validation\ValidationException;
use App\Modules\Quotations\Application\DTOs\QuotationProductInput;
use App\Modules\Products\Domain\Repositories\ProductRepositoryInterface;
use App\Modules\Quotations\Domain\Models\Quotation;
use App\Modules\Quotations\Domain\Repositories\QuotationRepositoryInterface;

class AddProductToQuotationUseCase
{
    public function __construct(
        private readonly QuotationRepositoryInterface $quotations,
        private readonly ProductRepositoryInterface $products,
    ) {
    }

    public function handle(int $supplierId, QuotationProductInput $input): Quotation
    {
        $quotation = $this->quotations->getOrCreateDraft($supplierId);

        $product = $this->products->find($input->productId);

        if ($product === null) {
            throw ValidationException::withMessages([
                'product_id' => trans('validation.exists', ['attribute' => 'product']),
            ]);
        }

        $product->loadMissing('family.supplier');

        $productSupplierId = $product->family?->supplier?->getKey();

        if ($productSupplierId === null || (int) $productSupplierId !== $supplierId) {
            throw ValidationException::withMessages([
                'product_id' => trans('apiMessages.forbidden'),
            ]);
        }

        $accessories = [];

        foreach ($input->accessories() as $accessoryInput) {
            $accessory = $this->products->find($accessoryInput->accessoryId);

            if ($accessory === null) {
                throw ValidationException::withMessages([
                    'accessories.*.accessory_id' => trans('validation.exists', ['attribute' => 'accessory']),
                ]);
            }

            $accessory->loadMissing('family.supplier');

            $accessorySupplierId = $accessory->family?->supplier?->getKey();

            if ($accessorySupplierId === null || (int) $accessorySupplierId !== $supplierId) {
                throw ValidationException::withMessages([
                    'accessories.*.accessory_id' => trans('apiMessages.forbidden'),
                ]);
            }

            $accessories[] = [
                'product' => $accessory,
                'attributes' => $accessoryInput->attributes(),
            ];
        }

        $this->quotations->addProduct($quotation, $product, $input->attributes(), $accessories);

        return $this->quotations->refreshTotals($quotation);
    }
}
