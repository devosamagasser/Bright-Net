<?php

namespace App\Modules\Specifications\Presentation\Http\Controllers;

use Illuminate\Http\Request;
use App\Modules\Specifications\Domain\Models\{
    SpecificationItem,
    SpecificationItemAccessory
};
use App\Modules\Specifications\Presentation\Http\Requests\{
    AddSpecificationAccessoryRequest,
    UpdateSpecificationAccessoryRequest
};
use App\Modules\Specifications\Application\UseCases\{
    AddAccessoryToSpecificationItemUseCase,
    RemoveSpecificationAccessoryUseCase,
    UpdateSpecificationAccessoryUseCase
};
use App\Modules\Specifications\Presentation\Resources\SpecificationResource;
use App\Modules\Shared\Support\Helper\ApiResponse;

class SpecificationItemAccessoryController
{
    public function __construct(
        private readonly AddAccessoryToSpecificationItemUseCase $addAccessory,
        private readonly UpdateSpecificationAccessoryUseCase $updateAccessory,
        private readonly RemoveSpecificationAccessoryUseCase $removeAccessory,
    ) {
    }

    public function store(AddSpecificationAccessoryRequest $request, SpecificationItem $item)
    {
        $spec = $this->addAccessory->handle(
            $item,
            $request->toInput(),
            $request->user()->company_id
        );

        return ApiResponse::created(
            SpecificationResource::make($spec->load('items.accessories'))->resolve()
        );
    }

    public function update(UpdateSpecificationAccessoryRequest $request, SpecificationItemAccessory $accessory)
    {
        $spec = $this->updateAccessory->handle(
            $accessory,
            $request->toInput(),
            $request->user()->company_id
        );

        return ApiResponse::updated(
            SpecificationResource::make($spec->load('items.accessories'))->resolve()
        );
    }

    public function destroy(Request $request, SpecificationItemAccessory $accessory)
    {
        $spec = $this->removeAccessory->handle(
            $accessory,
            $request->user()->company_id
        );

        return ApiResponse::success(
            SpecificationResource::make($spec->load('items.accessories'))->resolve()
        );
    }
}


