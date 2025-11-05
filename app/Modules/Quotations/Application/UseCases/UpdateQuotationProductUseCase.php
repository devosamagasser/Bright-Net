<?php

namespace App\Modules\Quotations\Application\UseCases;

use Illuminate\Validation\ValidationException;
use App\Modules\Quotations\Application\DTOs\QuotationProductUpdateInput;
use App\Modules\Quotations\Domain\Models\{
    Quotation,
    QuotationProduct,
};
use App\Modules\Quotations\Domain\Repositories\QuotationRepositoryInterface;
use App\Modules\Quotations\Domain\ValueObjects\QuotationStatus;

class UpdateQuotationProductUseCase
{
    public function __construct(
        private readonly QuotationRepositoryInterface $quotations,
    ) {
    }

    public function handle(QuotationProduct $item, QuotationProductUpdateInput $input, int $supplierId, int $ownerId): Quotation
    {
        $quotation = $item->quotation;

        $this->assertEditable($quotation, $supplierId, $ownerId);

        $this->quotations->updateProduct($item, $input->attributes());

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
