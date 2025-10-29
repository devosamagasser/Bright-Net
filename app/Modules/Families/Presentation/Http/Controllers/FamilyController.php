<?php

namespace App\Modules\Families\Presentation\Http\Controllers;

use Illuminate\Http\Request;
use App\Modules\Shared\Support\Helper\ApiResponse;
use App\Modules\Families\Presentation\Resources\FamilyResource;
use App\Modules\Families\Presentation\Http\Requests\{StoreFamilyRequest, UpdateFamilyRequest};
use App\Modules\Families\Application\UseCases\{
    CreateFamilyUseCase,
    DeleteFamilyUseCase,
    ListFamiliesUseCase,
    ShowFamilyUseCase,
    UpdateFamilyUseCase,
};
use App\Modules\Families\Application\DTOs\FamilyInput;
use App\Modules\Families\Domain\Models\Family;

class FamilyController
{
    public function __construct(
        private readonly CreateFamilyUseCase $createFamily,
        private readonly UpdateFamilyUseCase $updateFamily,
        private readonly DeleteFamilyUseCase $deleteFamily,
        private readonly ShowFamilyUseCase $showFamily,
        private readonly ListFamiliesUseCase $listFamilies,
    ) {
    }

    public function index(int $subcategory, Request $request)
    {
        $supplierId = $request->query('supplier_id');
        $supplierId = is_numeric($supplierId) ? (int) $supplierId : null;

        $families = $this->listFamilies->handle($subcategory, $supplierId);

        return ApiResponse::success(
            FamilyResource::collection($families)->resolve()
        );
    }

    public function store(StoreFamilyRequest $request)
    {
        $input = FamilyInput::fromArray($request->validated());
        $family = $this->createFamily->handle($input);

        return ApiResponse::created(
            FamilyResource::make($family)->resolve()
        );
    }

    public function show(Family $family)
    {
        $familyData = $this->showFamily->handle((int) $family->getKey());

        return ApiResponse::success(
            FamilyResource::make($familyData)->resolve()
        );
    }

    public function update(UpdateFamilyRequest $request, Family $family)
    {
        $input = FamilyInput::fromArray($request->validated());
        $familyData = $this->updateFamily->handle($family, $input);

        return ApiResponse::updated(
            FamilyResource::make($familyData)->resolve()
        );
    }

    public function destroy(Family $family)
    {
        $this->deleteFamily->handle($family);

        return ApiResponse::deleted();
    }
}
