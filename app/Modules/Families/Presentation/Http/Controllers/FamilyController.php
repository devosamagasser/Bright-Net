<?php

namespace App\Modules\Families\Presentation\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Modules\Shared\Support\Helper\ApiResponse;
use App\Modules\Families\Application\DTOs\FamilyInput;
use App\Modules\Families\Application\UseCases\{
    CreateFamilyUseCase,
    DeleteFamilyUseCase,
    ListFamiliesUseCase,
    ShowFamilyUseCase,
    UpdateFamilyUseCase
};
use App\Modules\Families\Presentation\Resources\FamilyResource;
use App\Modules\Families\Presentation\Http\Requests\{
    StoreFamilyRequest,
    UpdateFamilyRequest
};

class FamilyController
{
    public function __construct(
        private readonly ListFamiliesUseCase $listFamilies,
        private readonly ShowFamilyUseCase $showFamily,
        private readonly CreateFamilyUseCase $createFamily,
        private readonly UpdateFamilyUseCase $updateFamily,
        private readonly DeleteFamilyUseCase $deleteFamily,
    ) {
    }

    public function index(Request $request, int $subcategory)
    {
        $perPage = (int) $request->query('per_page', 15);
        $perPage = max(1, min(100, $perPage));
        $supplierId = $request->query('supplier_id');
        $supplierId = $supplierId !== null ? (int) $supplierId : null;

        $paginator = $this->listFamilies->handle($subcategory, $perPage, $supplierId);

        return ApiResponse::success(
            FamilyResource::collection($paginator)->resource
        );
    }

    public function store(StoreFamilyRequest $request)
    {
        $input = FamilyInput::fromArray($request->validated());
        $family = $this->createFamily->handle($input);

        return ApiResponse::success(
            FamilyResource::make($family),
            __('apiMessages.created'),
            Response::HTTP_CREATED,
        );
    }

    public function show(int $family)
    {
        $dto = $this->showFamily->handle($family);

        return ApiResponse::success(FamilyResource::make($dto));
    }

    public function update(UpdateFamilyRequest $request, int $family)
    {
        $input = FamilyInput::fromArray($request->validated());
        $dto = $this->updateFamily->handle($family, $input);

        return ApiResponse::success(
            FamilyResource::make($dto),
            __('apiMessages.updated'),
        );
    }

    public function destroy(int $family)
    {
        $this->deleteFamily->handle($family);

        return ApiResponse::deleted();
    }
}
