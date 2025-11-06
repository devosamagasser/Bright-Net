<?php

namespace App\Modules\Quotations\Application\UseCases;

use Illuminate\Validation\ValidationException;
use App\Modules\Quotations\Application\DTOs\QuotationInput;
use App\Modules\Quotations\Domain\Models\Quotation;
use App\Modules\Quotations\Domain\Repositories\QuotationRepositoryInterface;
use App\Modules\Quotations\Domain\ValueObjects\QuotationStatus;

class UpdateQuotationUseCase
{
    public function __construct(
        private readonly QuotationRepositoryInterface $quotations,
    ) {
    }

    public function handle(int $supplierId, QuotationInput $input): Quotation
    {
        $quotation = $this->quotations->getOrCreateDraft($supplierId);

        if ($quotation->status !== QuotationStatus::DRAFT) {
            throw ValidationException::withMessages([
                'quotation' => trans('apiMessages.forbidden'),
            ]);
        }

        $updated = $this->quotations->update($quotation, $input->attributes());

        return $this->quotations->refreshTotals($updated);
    }
}
