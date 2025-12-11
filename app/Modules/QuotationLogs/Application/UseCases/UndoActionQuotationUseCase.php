<?php

namespace App\Modules\QuotationLogs\Application\UseCases;

use App\Modules\Quotations\Domain\Models\QuotationProduct;
use App\Modules\QuotationLogs\Domain\Services\UndoService;
use Illuminate\Validation\ValidationException;
use App\Modules\Quotations\Domain\Models\Quotation;

class UndoActionQuotationUseCase
{
    public function __construct(
        private readonly UndoService $undoService,
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
            $lastLog = $quotation->logs()
                ->when($currentQuotationOperation, fn($query) =>
                    $query->where('id', '<', $currentQuotationOperation)
                )
                ->latest()
                ->first();
            if(!$lastLog) {
                throw ValidationException::withMessages([
                    'quotation' => trans('apiMessages.no_undo_available'),
                ]);
            }
        return $this->undoService->make($lastLog, $quotation);
    }
}
