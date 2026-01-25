<?php

namespace App\Modules\Quotations\Application\UseCases;

use Illuminate\Database\Eloquent\Model;
use App\Modules\Quotations\Application\Concerns\AssertsQuotationEditable;
use App\Modules\Quotations\Application\DTOs\QuotationInput;
use App\Modules\Quotations\Domain\Models\Quotation;
use App\Modules\Quotations\Domain\Repositories\QuotationRepositoryInterface;

class UpdateQuotationUseCase
{
    use AssertsQuotationEditable;

    public function __construct(
        private readonly QuotationRepositoryInterface $quotations,
    ) {
    }

    public function handle(Quotation $quotation, QuotationInput $input): Quotation
    {
        $this->assertEditable($quotation, request('supplier_id'));

        $updated = $this->quotations->update($quotation, $input->attributes());

        return $this->quotations->refreshTotals($updated);
    }
}
