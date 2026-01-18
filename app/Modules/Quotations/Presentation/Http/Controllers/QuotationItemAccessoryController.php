<?php

namespace App\Modules\Quotations\Presentation\Http\Controllers;

use Illuminate\Http\Request;
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
            $request->input('supplier_id')
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
            $request->input('supplier_id')
        );

        return ApiResponse::updated(
            QuotationResource::make($quotation)->resolve()
        );
    }

    public function destroy(Request $request, QuotationProductAccessory $accessory)
    {
        $quotation = $this->removeAccessory->handle(
            $accessory,
            $request->input('supplier_id')
        );

        return ApiResponse::success(
            QuotationResource::make($quotation)->resolve()
        );
    }
}
