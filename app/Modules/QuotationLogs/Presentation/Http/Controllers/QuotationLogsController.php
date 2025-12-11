<?php

namespace App\Modules\QuotationLogs\Presentation\Http\Controllers;

use Illuminate\Validation\ValidationException;
use App\Modules\Shared\Support\Helper\ApiResponse;
use App\Modules\Quotations\Domain\Models\Quotation;
use App\Modules\Quotations\Presentation\Resources\QuotationResource;
use App\Modules\QuotationLogs\Application\UseCases\RedoActionQuotationUseCase;
use App\Modules\QuotationLogs\Application\UseCases\UndoActionQuotationUseCase;

class QuotationLogsController
{
    public function __construct(
        private readonly UndoActionQuotationUseCase $undoAction,
        private readonly RedoActionQuotationUseCase $redoAction,
    ) {
    }

    public function undo(Quotation $quotation)
    {
        $undoQuotation = $this->undoAction->handle(
            $this->supplierId(),
            $quotation
        );

        return ApiResponse::updated(
            QuotationResource::make($undoQuotation)->resolve()
        );
    }

    public function redo(Quotation $quotation)
    {
        $undoQuotation = $this->redoAction->handle(
            $this->supplierId(),
            $quotation
        );

        return ApiResponse::updated(
            QuotationResource::make($undoQuotation)->resolve()
        );
    }

    private function supplierId(): int
    {
        $user = auth()->user();

        if ($user !== null && method_exists($user, 'company')) {
            $user->loadMissing('company.supplier');
        }

        if ($user === null || ! method_exists($user, 'company') || $user->company === null || $user->company->supplier === null) {
            throw ValidationException::withMessages([
                'supplier' => trans('apiMessages.forbidden'),
            ]);
        }

        return (int) $user->company->supplier->getKey();
    }
}
