<?php
namespace App\Modules\QuotationLogs\Domain\Services\UndoActions;


use App\Modules\Quotations\Domain\Models\QuotationProduct;
use App\Modules\QuotationLogs\Domain\Models\QuotationActivityLog;

class RevertUpdateQuotationProductService
{
    public function execute(QuotationActivityLog $log):QuotationProduct
    {
        $oldData = $log->old_object;

        $quotationProduct = QuotationProduct::find($log->loggable_id);

        if ($quotationProduct) {
            $quotationProduct->update($oldData);
            $log->update([
                'old_object' => $log->new_object,
                'new_object' => $oldData
            ]);
        }

        return $quotationProduct;
    }
}