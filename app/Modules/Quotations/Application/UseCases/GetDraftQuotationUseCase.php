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

    public function handle($user): Quotation
    {
        $quotation = $this->quotations->getOrCreateDraft($user);

        // Only refresh totals if products exist, otherwise just load relations
        if ($quotation->wasRecentlyCreated || $quotation->products()->doesntExist()) {
            return $this->quotations->loadRelations($quotation);
        }

        return $this->quotations->refreshTotals($quotation);
    }
}
