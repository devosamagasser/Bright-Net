<?php

namespace App\Modules\Quotations\Application\UseCases;

use App\Modules\Quotations\Domain\Models\Quotation;
use App\Modules\Quotations\Domain\Repositories\QuotationRepositoryInterface;

class GetDraftQuotationUseCase
{
    public function __construct(
        private readonly QuotationRepositoryInterface $quotations,
    ) {
    }

    public function handle(int $supplierId): Quotation
    {
        $quotation = $this->quotations->getOrCreateDraft($supplierId);

        return $this->quotations->refreshTotals($quotation);
    }
}
