<?php

namespace App\Modules\Quotations\Presentation\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use App\Modules\Quotations\Domain\Models\Quotation;
use App\Modules\Quotations\Presentation\Http\Requests\{UpdateQuotationFlagsRequest,
    UpdateQuotationDetailsRequest,
    UpdateQuotationRequest};
use App\Modules\Quotations\Application\UseCases\{GetDraftQuotationUseCase,
    UpdateQuotationFlagsUseCase,
    UpdateQuotationDetailsUseCase,
    UpdateQuotationUseCase};
use App\Modules\Quotations\Presentation\Resources\QuotationResource;
use App\Modules\Shared\Support\Helper\ApiResponse;

class QuotationController
{
    public function __construct(
        private readonly UpdateQuotationFlagsUseCase $updateFlags,
        private readonly UpdateQuotationDetailsUseCase $updateDetails,
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

    public function update(UpdateQuotationRequest $request)
    {
        $quotation = $this->updateDraft->handle(
            $request->user(),
            $request->toInput()
        );

        return ApiResponse::updated(
            QuotationResource::make($quotation)->resolve()
        );
    }

    public function updateFlags(UpdateQuotationFlagsRequest $request, Quotation $quotation)
    {
        $quotation = $this->updateFlags->handle(
            $quotation,
            $request->toInput(),
            $request->supplier_id
        );

        return ApiResponse::updated(
            QuotationResource::make($quotation)->resolve()
        );
    }

    public function updateDetails(UpdateQuotationDetailsRequest $request, Quotation $quotation)
    {
        $quotation = $this->updateDetails->handle(
            $quotation,
            $request->toInput(),
            $request->supplier_id
        );

        return ApiResponse::updated(
            QuotationResource::make($quotation)->resolve()
        );
    }

}



