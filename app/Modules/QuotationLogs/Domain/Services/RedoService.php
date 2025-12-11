<?php

namespace App\Modules\QuotationLogs\Domain\Services;

use App\Modules\Quotations\Domain\Models\Quotation;
use App\Modules\Quotations\Domain\Models\QuotationProduct;
use App\Modules\QuotationLogs\Domain\Models\QuotationActivityLog;
use App\Modules\QuotationLogs\Domain\ValueObjects\QuotationActivityType;
use App\Modules\QuotationLogs\Domain\Services\UndoActions\RemoveQuotationProductService;
use App\Modules\QuotationLogs\Domain\Services\UndoActions\ReviveQuotationProductService;
use App\Modules\QuotationLogs\Domain\Services\UndoActions\RevertUpdateQuotationProductService;

class RedoService
{
    public function make(QuotationActivityLog $currentLog, QuotationActivityLog|null $lastLog = null, Quotation $quotation): QuotationProduct|null      
    {
        $executer = match ($currentLog->activity_type) {
            QuotationActivityType::CREATE_PRODUCT => app()->make(ReviveQuotationProductService::class),
            QuotationActivityType::UPDATE_PRODUCT => app()->make(RevertUpdateQuotationProductService::class),
            QuotationActivityType::DELETE_PRODUCT => app()->make(RemoveQuotationProductService::class),
            default => null,
        };
        $quotationProduct = $executer?->execute($currentLog);
        if($quotationProduct === null) {
            return null;
        }

        $this->updateQuotationStatusLog($quotation, $lastLog);
        
        return $quotationProduct;
    }

    public function updateQuotationStatusLog(Quotation $quotation, QuotationActivityLog|null $lastLog = null): void
    {
        $quotation->update([
            'log_status' => $lastLog?->id ?? 0,
        ]);
    }
}

