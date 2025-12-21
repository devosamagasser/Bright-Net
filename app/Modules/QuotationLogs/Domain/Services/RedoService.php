<?php

namespace App\Modules\QuotationLogs\Domain\Services;

use App\Modules\Quotations\Domain\Models\Quotation;
use App\Modules\Quotations\Domain\Models\QuotationProduct;
use App\Modules\QuotationLogs\Domain\Models\QuotationActivityLog;
use App\Modules\QuotationLogs\Domain\ValueObjects\QuotationActivityType;
use App\Modules\QuotationLogs\Domain\Services\UndoRedoActions\{
    RemoveQuotationItemAction,
    ReviveQuotationItemAction,
    RevertQuotationItemUpdateAction,
};
use App\Modules\Quotations\Domain\Models\QuotationProductAccessory;

class RedoService
{
    public function make(QuotationActivityLog $currentLog, QuotationActivityLog|null $lastLog = null, Quotation $quotation): QuotationProduct|QuotationProductAccessory|null
    {
    
        $executer = match ($currentLog->activity_type) {
            QuotationActivityType::CREATE => app()->make(ReviveQuotationItemAction::class),
            QuotationActivityType::UPDATE => app()->make(RevertQuotationItemUpdateAction::class),
            QuotationActivityType::DELETE => app()->make(RemoveQuotationItemAction::class),
            default => null,
        };
        $quotationItem = $executer?->execute($currentLog);
        if($quotationItem === null) {
            return null;
        }

        $this->updateQuotationStatusLog($quotation, $lastLog);

        return $quotationItem;
    }

    public function updateQuotationStatusLog(Quotation $quotation, QuotationActivityLog|null $lastLog = null): void
    {
        $quotation->update([
            'log_status' => $lastLog?->id ?? 0,
        ]);
    }
}

