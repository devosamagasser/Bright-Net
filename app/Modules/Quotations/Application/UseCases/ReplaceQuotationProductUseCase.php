<?php

namespace App\Modules\Quotations\Application\UseCases;

use Illuminate\Validation\ValidationException;
use App\Modules\Quotations\Application\DTOs\QuotationProductInput;
use App\Modules\Products\Domain\Repositories\ProductRepositoryInterface;
use App\Modules\Quotations\Domain\Models\{Quotation, QuotationProduct};
use App\Modules\Quotations\Domain\Repositories\QuotationRepositoryInterface;
use App\Modules\Quotations\Domain\ValueObjects\QuotationStatus;

class ReplaceQuotationProductUseCase
{
    public function __construct(
        private readonly QuotationRepositoryInterface $quotations,
        private readonly ProductRepositoryInterface $products,
    ) {
    }

    public function handle(QuotationProduct $current, QuotationProductInput $input, int $supplierId): Quotation
    {
        $quotation = $current->quotation;

        $this->assertEditable($quotation, $supplierId);

        $replacement = $this->products->find($input->productId);

        if ($replacement === null) {
            throw ValidationException::withMessages([
                'product_id' => trans('validation.exists', ['attribute' => 'product']),
            ]);
        }

        $replacement->loadMissing('family.supplier');

        $replacementSupplierId = $replacement->family?->supplier?->getKey();

        if ($replacementSupplierId === null || (int) $replacementSupplierId !== $supplierId) {
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

        $this->quotations->replaceProduct($current, $replacement, $input->attributes(), $accessories);

        return $this->quotations->refreshTotals($quotation);
    }

    private function assertEditable(Quotation $quotation, int $supplierId): void
    {
        if ((int) $quotation->supplier_id !== $supplierId || $quotation->status !== QuotationStatus::DRAFT) {
            throw ValidationException::withMessages([
                'quotation' => trans('apiMessages.forbidden'),
            ]);
        }
    }
}
