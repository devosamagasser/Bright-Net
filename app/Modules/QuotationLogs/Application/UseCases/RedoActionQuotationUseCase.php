<?php

namespace App\Modules\QuotationLogs\Application\UseCases;

use App\Modules\Quotations\Domain\Models\QuotationProduct;
use App\Modules\QuotationLogs\Domain\Services\RedoService;
use Illuminate\Validation\ValidationException;
use App\Modules\Quotations\Domain\Models\Quotation;

class RedoActionQuotationUseCase
{
    public function __construct(
        private readonly RedoService $redoService,
    ) {
    }

    public function handle(int $supplierId, Quotation $quotation): QuotationProduct
    {
        if($quotation->supplier_id !== $supplierId) {
            throw ValidationException::withMessages([
                'supplier' => trans('apiMessages.forbidden'),
            ]);
        }
        $currentQuotationOperation = $quotation->log_status;
        if(!$currentQuotationOperation) {
            throw ValidationException::withMessages([
                'quotation' => trans('apiMessages.no_redo_available'),
            ]);
        }

        $currentStatusAndLastStatus = $quotation->logs()
            ->with('loggable')
            ->where('id', '>=', $currentQuotationOperation)
            ->limit(2)
            ->get();
        return $this->redoService->make(
        $currentStatusAndLastStatus->first(), 
        $currentStatusAndLastStatus->get(1) ?? null, 
        $quotation
        );
    }
}
