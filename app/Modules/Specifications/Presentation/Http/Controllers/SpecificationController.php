<?php

namespace App\Modules\Specifications\Presentation\Http\Controllers;

use Illuminate\Http\Request;
use App\Modules\Specifications\Domain\Models\Specification;
use App\Modules\Specifications\Presentation\Http\Requests\{
    UpdateSpecificationDetailsRequest,
    UpdateSpecificationRequest
};
use App\Modules\Specifications\Application\UseCases\{
    GetDraftSpecificationUseCase,
    UpdateSpecificationUseCase
};
use App\Modules\Specifications\Presentation\Resources\SpecificationResource;
use App\Modules\Shared\Support\Helper\ApiResponse;

class SpecificationController
{
    public function __construct(
        private readonly GetDraftSpecificationUseCase $showDraft,
        private readonly UpdateSpecificationUseCase $updateSpecification,
    ) {
    }

    public function show(Request $request)
    {
        $spec = $this->showDraft->handle(
            $request->user(),
        );

        return ApiResponse::success(
            SpecificationResource::make($spec->load('items.accessories'))->resolve()
        );
    }

    public function update(UpdateSpecificationRequest $request, Specification $specification)
    {
        $updated = $this->updateSpecification->handle(
            $specification,
            $request->toInput(),
            $request->user()->company_id
        );

        return ApiResponse::updated(
            SpecificationResource::make($updated->load('items.accessories'))->resolve()
        );
    }
}


