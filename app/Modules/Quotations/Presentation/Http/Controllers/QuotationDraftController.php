<?php

namespace App\Modules\Quotations\Presentation\Http\Controllers;

use Illuminate\Validation\ValidationException;
use App\Modules\Quotations\Presentation\Http\Requests\{
    AddQuotationProductRequest,
    UpdateQuotationRequest,
};
use App\Modules\Quotations\Application\UseCases\{
    AddProductToQuotationUseCase,
    GetDraftQuotationUseCase,
    UpdateQuotationUseCase,
};
use App\Modules\Quotations\Presentation\Resources\QuotationResource;
use App\Modules\Shared\Support\Helper\ApiResponse;

class QuotationDraftController
{
    public function __construct(
        private readonly GetDraftQuotationUseCase $showDraft,
        private readonly UpdateQuotationUseCase $updateDraft,
        private readonly AddProductToQuotationUseCase $addProduct,
    ) {
    }

    public function show()
    {
        $quotation = $this->showDraft->handle(
            $this->supplierId(),
            $this->ownerId(),
        );

        return ApiResponse::success(
            QuotationResource::make($quotation)->resolve()
        );
    }

    public function update(UpdateQuotationRequest $request)
    {
        $quotation = $this->updateDraft->handle(
            $this->supplierId(),
            $this->ownerId(),
            $request->toInput()
        );

        return ApiResponse::updated(
            QuotationResource::make($quotation)->resolve()
        );
    }

    public function storeItem(AddQuotationProductRequest $request)
    {
        $quotation = $this->addProduct->handle(
            $this->supplierId(),
            $this->ownerId(),
            $request->toInput()
        );

        return ApiResponse::created(
            QuotationResource::make($quotation)->resolve()
        );
    }

    private function ownerId(): int
    {
        $userId = auth()->id();

        if ($userId === null) {
            throw ValidationException::withMessages([
                'user' => trans('apiMessages.unauthorized'),
            ]);
        }

        return (int) $userId;
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
