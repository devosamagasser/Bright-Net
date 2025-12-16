<?php
namespace App\Modules\QuotationLogs\Domain\Services\UndoRedoActions;


use App\Modules\Quotations\Domain\Models\QuotationProduct;
use App\Modules\QuotationLogs\Domain\Models\QuotationActivityLog;

class RemoveQuotationItemAction
{
    public function execute(QuotationActivityLog $log):QuotationProduct
    {
        $quotationProduct = $log->loggable;

        if ($quotationProduct) {
            $quotationProduct->delete();
        }

        return $quotationProduct;
    }
}
