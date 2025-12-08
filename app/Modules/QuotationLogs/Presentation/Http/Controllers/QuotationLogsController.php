<?php

namespace App\Modules\Quotations\Presentation\Http\Controllers;

use App\Modules\QuotationLogs\Application\UseCases\UndoActionQuotationUseCase;
use App\Modules\Quotations\Domain\Models\Quotation;
use Illuminate\Validation\ValidationException;
use App\Modules\Quotations\Domain\Models\QuotationProduct;
use App\Modules\Quotations\Presentation\Http\Requests\{
    ReplaceQuotationProductRequest,
    UpdateQuotationProductRequest,
};
use App\Modules\Quotations\Application\UseCases\{
    RemoveQuotationProductUseCase,
    ReplaceQuotationProductUseCase,
    UpdateQuotationProductUseCase,
};
use App\Modules\Quotations\Presentation\Resources\QuotationResource;
use App\Modules\Shared\Support\Helper\ApiResponse;

class QuotationLogsController
{
    public function __construct(
        private readonly UndoActionQuotationUseCase $undoAction,
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
