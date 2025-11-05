<?php

namespace App\Modules\Quotations\Presentation\Http\Controllers;

use Illuminate\Validation\ValidationException;
use App\Modules\Quotations\Domain\Models\{
    QuotationProduct,
    QuotationProductAccessory,
};
use App\Modules\Quotations\Presentation\Http\Requests\{
    AddQuotationAccessoryRequest,
    UpdateQuotationAccessoryRequest,
};
use App\Modules\Quotations\Application\UseCases\{
    AddAccessoryToQuotationProductUseCase,
    RemoveQuotationAccessoryUseCase,
    UpdateQuotationAccessoryUseCase,
};
use App\Modules\Quotations\Presentation\Resources\QuotationResource;
use App\Modules\Shared\Support\Helper\ApiResponse;

class QuotationItemAccessoryController
{
    public function __construct(
        private readonly AddAccessoryToQuotationProductUseCase $addAccessory,
        private readonly UpdateQuotationAccessoryUseCase $updateAccessory,
        private readonly RemoveQuotationAccessoryUseCase $removeAccessory,
    ) {
    }

    public function store(AddQuotationAccessoryRequest $request, QuotationProduct $item)
    {
        $quotation = $this->addAccessory->handle(
            $item,
            $request->toInput(),
            $this->supplierId(),
            $this->ownerId()
        );

        return ApiResponse::created(
            QuotationResource::make($quotation)->resolve()
        );
    }

    public function update(UpdateQuotationAccessoryRequest $request, QuotationProductAccessory $accessory)
    {
        $quotation = $this->updateAccessory->handle(
            $accessory,
            $request->toInput(),
            $this->supplierId(),
            $this->ownerId()
        );

        return ApiResponse::updated(
            QuotationResource::make($quotation)->resolve()
        );
    }

    public function destroy(QuotationProductAccessory $accessory)
    {
        $quotation = $this->removeAccessory->handle(
            $accessory,
            $this->supplierId(),
            $this->ownerId()
        );

        return ApiResponse::updated(
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
