<?php

namespace App\Modules\Quotations\Presentation\Http\Controllers;

use Illuminate\Http\Request;
use App\Modules\Quotations\Domain\Models\QuotationProduct;
use App\Modules\Quotations\Presentation\Http\Requests\{AddQuotationProductRequest,
    ReplaceQuotationProductRequest,
    UpdateQuotationProductRequest};
use App\Modules\Quotations\Application\UseCases\{AddProductToQuotationUseCase,
    RemoveQuotationProductUseCase,
    ReplaceQuotationProductUseCase,
    UpdateQuotationProductUseCase};
use App\Modules\Quotations\Presentation\Resources\QuotationResource;
use App\Modules\Shared\Support\Helper\ApiResponse;

class QuotationItemController
{
    public function __construct(
        private readonly UpdateQuotationProductUseCase $updateItem,
        private readonly RemoveQuotationProductUseCase $removeItem,
        private readonly ReplaceQuotationProductUseCase $replaceItem,
        private readonly AddProductToQuotationUseCase $addProduct,

    ) {
    }

    public function storeItem(AddQuotationProductRequest $request)
    {
        $quotation = $this->addProduct->handle(
            $request->user(),
            $request->toInput()
        );

        return ApiResponse::created(
            QuotationResource::make($quotation)->resolve()
        );
    }

    public function update(UpdateQuotationProductRequest $request, QuotationProduct $item)
    {
        $quotation = $this->updateItem->handle(
            $item,
            $request->toInput(),
            $request->input('supplier_id')
        );

        return ApiResponse::updated(
            QuotationResource::make($quotation)->resolve()
        );
    }

    public function destroy(Request $request, QuotationProduct $item)
    {
        $quotation = $this->removeItem->handle(
            $item,
            $request->input('supplier_id')
        );

        return ApiResponse::success(
            QuotationResource::make($quotation)->resolve()
        );
    }

    public function replace(ReplaceQuotationProductRequest $request, QuotationProduct $item)
    {
        $quotation = $this->replaceItem->handle(
            $item,
            $request->toInput(),
            $request->input('supplier_id')
        );

        return ApiResponse::updated(
            QuotationResource::make($quotation)->resolve()
        );
    }

}
