<?php

namespace App\Modules\Quotations\Application\Concerns;

use Illuminate\Validation\ValidationException;
use App\Modules\Quotations\Domain\Models\Quotation;
use App\Modules\Quotations\Domain\ValueObjects\QuotationStatus;

trait AssertsQuotationEditable
{
    /**
     * Assert that the quotation is editable by the given supplier
     *
     * @throws ValidationException
     */
    protected function assertEditable(Quotation $quotation, int $supplierId): void
    {
        if ((int) $quotation->supplier_id !== $supplierId || $quotation->status !== QuotationStatus::DRAFT) {
            throw ValidationException::withMessages([
                'quotation' => trans('apiMessages.forbidden'),
            ]);
        }
    }
}

