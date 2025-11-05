<?php

namespace App\Modules\Quotations\Application\UseCases;

use Illuminate\Validation\ValidationException;
use App\Modules\Quotations\Application\DTOs\QuotationAccessoryInput;
use App\Modules\Products\Domain\Repositories\ProductRepositoryInterface;
use App\Modules\Quotations\Domain\Models\{
    Quotation,
    QuotationProduct,
};
use App\Modules\Quotations\Domain\Repositories\QuotationRepositoryInterface;
use App\Modules\Quotations\Domain\ValueObjects\QuotationStatus;

class AddAccessoryToQuotationProductUseCase
{
    public function __construct(
        private readonly QuotationRepositoryInterface $quotations,
        private readonly ProductRepositoryInterface $products,
    ) {
    }

    public function handle(QuotationProduct $item, QuotationAccessoryInput $input, int $supplierId, int $ownerId): Quotation
    {
        $quotation = $item->quotation;

        $this->assertEditable($quotation, $supplierId, $ownerId);

        $accessory = $this->products->find($input->accessoryId);

        if ($accessory === null) {
            throw ValidationException::withMessages([
                'accessory_id' => trans('validation.exists', ['attribute' => 'accessory']),
            ]);
        }

        $accessory->loadMissing('family.supplier');

        $accessorySupplierId = $accessory->family?->supplier?->getKey();

        if ($accessorySupplierId === null || (int) $accessorySupplierId !== $supplierId) {
            throw ValidationException::withMessages([
                'accessory_id' => trans('apiMessages.forbidden'),
            ]);
        }

        $this->quotations->addAccessory($item, $accessory, $input->attributes());

        return $this->quotations->refreshTotals($quotation);
    }

    private function assertEditable(Quotation $quotation, int $supplierId, int $ownerId): void
    {
        if ((int) $quotation->supplier_id !== $supplierId || $quotation->status !== QuotationStatus::DRAFT) {
            throw ValidationException::withMessages([
                'quotation' => trans('apiMessages.forbidden'),
            ]);
        }

        if ((int) $quotation->owner_id !== $ownerId) {
            $quotation->owner_id = $ownerId;
            $quotation->save();
        }
    }
}
