<?php

namespace App\Modules\QuotationLogs\Application\UseCases;

use Illuminate\Validation\ValidationException;
use App\Modules\Quotations\Domain\Models\Quotation;
use App\Modules\QuotationLogs\Domain\Services\UndoService;
use App\Modules\Quotations\Domain\Repositories\QuotationRepositoryInterface;

class UndoActionQuotationUseCase
{
    public function __construct(
        private readonly UndoService $undoService,
        private readonly QuotationRepositoryInterface $draftQuotation,
    ) {
    }

    public function handle(int $supplierId, Quotation $quotation): Quotation
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
            $this->undoService->make($lastLog, $quotation);

        return $this->draftQuotation->refreshTotals($quotation);
    }
}
