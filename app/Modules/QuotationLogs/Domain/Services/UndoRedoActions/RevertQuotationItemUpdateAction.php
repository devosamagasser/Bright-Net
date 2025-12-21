<?php
namespace App\Modules\QuotationLogs\Domain\Services\UndoRedoActions;


use App\Modules\Quotations\Domain\Models\QuotationProduct;
use App\Modules\QuotationLogs\Domain\Models\QuotationActivityLog;
use App\Modules\Quotations\Domain\Models\QuotationProductAccessory;

class RevertQuotationItemUpdateAction
{
    public function execute(QuotationActivityLog $log):QuotationProduct|QuotationProductAccessory
    {
        $oldData = $log->old_object;

        $loggable = $log->loggable;
        $loggable->update($oldData);
        $log->update([
            'old_object' => $log->new_object,
            'new_object' => $oldData
        ]);

        return $loggable;
    }
}
