<?php
namespace App\Modules\QuotationLogs\Domain\Services\UndoRedoActions;


use App\Modules\Quotations\Domain\Models\QuotationProduct;
use App\Modules\QuotationLogs\Domain\Models\QuotationActivityLog;
use App\Modules\Quotations\Domain\Models\QuotationProductAccessory;

class RemoveQuotationItemAction
{
    public function execute(QuotationActivityLog $log):QuotationProduct|QuotationProductAccessory
    {
        $quotationProduct = $log->loggable;

        if ($quotationProduct) {
            $quotationProduct->delete();
        }
        return $quotationProduct;
    }
}
