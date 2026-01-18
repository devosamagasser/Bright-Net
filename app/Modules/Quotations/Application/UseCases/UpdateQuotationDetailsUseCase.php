<?php

namespace App\Modules\Quotations\Application\UseCases;

use App\Modules\Quotations\Application\Concerns\AssertsQuotationEditable;
use App\Modules\Quotations\Application\DTOs\QuotationInput;
use App\Modules\Quotations\Domain\Models\Quotation;
use App\Modules\Quotations\Domain\Repositories\QuotationRepositoryInterface;

class UpdateQuotationDetailsUseCase
{
    use AssertsQuotationEditable;

    public function __construct(
        private readonly QuotationRepositoryInterface $quotations,
    ) {
    }

    public function handle(Quotation $quotation, QuotationInput $input, int $supplierId): Quotation
    {
        $this->assertEditable($quotation, $supplierId);

        $updated = $this->quotations->update(
            $quotation,
            $input->attributes()
        );

        // تغيير الـ details لا يؤثر على totals، فاكتفي بالـ relations
        return $this->quotations->loadRelations($updated);
    }
}



