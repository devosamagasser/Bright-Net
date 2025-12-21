<?php

namespace App\Modules\QuotationLogs\Domain\Services;

use App\Modules\Quotations\Domain\Models\Quotation;
use App\Modules\Quotations\Domain\Models\{QuotationProduct, QuotationProductAccessory};
use App\Modules\QuotationLogs\Domain\Models\QuotationActivityLog;
use App\Modules\QuotationLogs\Domain\ValueObjects\QuotationActivityType;
use App\Modules\QuotationLogs\Domain\Services\UndoRedoActions\{
    RemoveQuotationItemAction,
    ReviveQuotationItemAction,
    RevertQuotationItemUpdateAction,
};

class UndoService
{
    public function make(QuotationActivityLog $lastLog, Quotation $quotation): QuotationProduct|QuotationProductAccessory|null
    {
        // dd($lastLog);
        $executer = match ($lastLog->activity_type) {
            QuotationActivityType::CREATE => app()->make(RemoveQuotationItemAction::class),
            QuotationActivityType::UPDATE => app()->make(RevertQuotationItemUpdateAction::class),
            QuotationActivityType::DELETE => app()->make(ReviveQuotationItemAction::class),
            default => null,
        };
        $quotationItem = $executer?->execute($lastLog);
        if($quotationItem === null) {
            return null;
        }
        $this->updateQuotationStatusLog($quotation, $lastLog);

        return $quotationItem;
    }

    public function updateQuotationStatusLog(Quotation $quotation, QuotationActivityLog $lastLog): void
    {
        $quotation->update([
            'log_status' => $lastLog->id,
        ]);
    }
}

