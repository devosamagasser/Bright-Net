<?php
namespace App\Modules\QuotationLogs\Domain\Services\UndoActions;


use App\Modules\Quotations\Domain\Models\QuotationProduct;
use App\Modules\QuotationLogs\Domain\Models\QuotationActivityLog;

class ReviveQuotationProductService
{
    public function execute(QuotationActivityLog $log):QuotationProduct
    {
        $quotationProduct = $log->loggable;
        if ($quotationProduct) {
            $quotationProduct->restore();
        }
        return $quotationProduct;
    }
}