<?php

namespace App\Modules\Quotations\Presentation\Http\Controllers;

use App\Modules\Quotations\Domain\Models\Quotation;
use Illuminate\Http\Request;
use App\Modules\Quotations\Presentation\Http\Requests\UpdateQuotationRequest;
use App\Modules\Quotations\Application\UseCases\{
    GetDraftQuotationUseCase,
    UpdateQuotationUseCase
};
use App\Modules\Quotations\Presentation\Resources\QuotationResource;
use App\Modules\Shared\Support\Helper\ApiResponse;

class QuotationController
{
    public function __construct(
        private readonly GetDraftQuotationUseCase $showDraft,
        private readonly UpdateQuotationUseCase $updateDraft,
    ) {
    }

    public function show(Request $request)
    {
        $quotation = $this->showDraft->handle(
            $request->user(),
        );

        return ApiResponse::success(
            QuotationResource::make($quotation)->resolve()
        );
    }

    public function update(UpdateQuotationRequest $request, Quotation $quotation)
    {
        $quotation = $this->updateDraft->handle(
            $quotation,
            $request->toInput()
        );

        return ApiResponse::updated(
            QuotationResource::make($quotation)->resolve()
        );
    }

//    public function updateFlags(UpdateQuotationFlagsRequest $request, Quotation $quotation)
//    {
//        $quotation = $this->updateFlags->handle(
//            $quotation,
//            $request->toInput(),
//            $request->supplier_id
//        );
//
//        return ApiResponse::updated(
//            QuotationResource::make($quotation)->resolve()
//        );
//    }

//    public function updateDetails(UpdateQuotationDetailsRequest $request, Quotation $quotation)
//    {
//        $quotation = $this->updateDetails->handle(
//            $quotation,
//            $request->toInput(),
//            $request->supplier_id
//        );
//
//        return ApiResponse::updated(
//            QuotationResource::make($quotation)->resolve()
//        );
//    }

}



