<?php

namespace App\Modules\Specifications\Presentation\Http\Controllers;

use Illuminate\Http\Request;
use App\Modules\Specifications\Domain\Models\SpecificationItem;
use App\Modules\Specifications\Presentation\Http\Requests\{
    AddSpecificationItemRequest,
    UpdateSpecificationItemRequest,
    ReplaceSpecificationItemRequest
};
use App\Modules\Specifications\Application\UseCases\{
    AddItemToSpecificationUseCase,
    RemoveSpecificationItemUseCase,
    ReplaceSpecificationItemUseCase,
    UpdateSpecificationItemUseCase
};
use App\Modules\Specifications\Presentation\Resources\SpecificationResource;
use App\Modules\Shared\Support\Helper\ApiResponse;

class SpecificationItemController
{
    public function __construct(
        private readonly AddItemToSpecificationUseCase $addItem,
        private readonly UpdateSpecificationItemUseCase $updateItem,
        private readonly ReplaceSpecificationItemUseCase $replaceItem,
        private readonly RemoveSpecificationItemUseCase $removeItem,
    ) {
    }

    public function storeItem(AddSpecificationItemRequest $request)
    {
        $spec = $this->addItem->handle(
            $request->user(),
            $request->toInput()
        );

        return ApiResponse::created(
            SpecificationResource::make($spec->load('items.accessories'))->resolve()
        );
    }

    public function update(UpdateSpecificationItemRequest $request, SpecificationItem $item)
    {
        $spec = $this->updateItem->handle(
            $item,
            $request->toInput(),
            $request->user()->company_id
        );

        return ApiResponse::updated(
            SpecificationResource::make($spec->load('items.accessories'))->resolve()
        );
    }

    public function destroy(Request $request, SpecificationItem $item)
    {
        $spec = $this->removeItem->handle(
            $item,
            $request->user()->company_id
        );

        return ApiResponse::success(
            SpecificationResource::make($spec->load('items.accessories'))->resolve()
        );
    }

    public function replace(ReplaceSpecificationItemRequest $request, SpecificationItem $item)
    {
        $spec = $this->replaceItem->handle(
            $item,
            $request->toInput(),
            $request->user()->company_id
        );

        return ApiResponse::updated(
            SpecificationResource::make($spec->load('items.accessories'))->resolve()
        );
    }
}


