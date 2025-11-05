<?php

namespace App\Modules\Quotations\Application\UseCases;

use Illuminate\Validation\ValidationException;
use App\Modules\Quotations\Domain\Models\{
    Quotation,
    QuotationProductAccessory,
};
use App\Modules\Quotations\Domain\Repositories\QuotationRepositoryInterface;
use App\Modules\Quotations\Domain\ValueObjects\QuotationStatus;

class RemoveQuotationAccessoryUseCase
{
    public function __construct(
        private readonly QuotationRepositoryInterface $quotations,
    ) {
    }

    public function handle(QuotationProductAccessory $accessory, int $supplierId, int $ownerId): Quotation
    {
        $quotation = $accessory->quotation;

        $this->assertEditable($quotation, $supplierId, $ownerId);

        $this->quotations->deleteAccessory($accessory);

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
