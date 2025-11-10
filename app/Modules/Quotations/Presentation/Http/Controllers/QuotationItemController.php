<?php

namespace App\Modules\Quotations\Presentation\Http\Controllers;

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

class QuotationItemController
{
    public function __construct(
        private readonly UpdateQuotationProductUseCase $updateItem,
        private readonly RemoveQuotationProductUseCase $removeItem,
        private readonly ReplaceQuotationProductUseCase $replaceItem,
    ) {
    }

    public function update(UpdateQuotationProductRequest $request, QuotationProduct $item)
    {
        $quotation = $this->updateItem->handle(
            $item,
            $request->toInput(),
            $this->supplierId()
        );

        return ApiResponse::updated(
            QuotationResource::make($quotation)->resolve()
        );
    }

    public function destroy(QuotationProduct $item)
    {
        $quotation = $this->removeItem->handle(
            $item,
            $this->supplierId()
        );

        return ApiResponse::deleted();
        // return ApiResponse::updated(
        //     QuotationResource::make($quotation)->resolve()
        // );
    }

    public function replace(ReplaceQuotationProductRequest $request, QuotationProduct $item)
    {
        $quotation = $this->replaceItem->handle(
            $item,
            $request->toInput(),
            $this->supplierId()
        );

        return ApiResponse::updated(
            QuotationResource::make($quotation)->resolve()
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
