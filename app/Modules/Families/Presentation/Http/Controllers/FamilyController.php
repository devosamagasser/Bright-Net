<?php

namespace App\Modules\Families\Presentation\Http\Controllers;

use App\Modules\Families\Application\UseCases\FamilyImportUseCase;
use App\Modules\Shared\Support\Helper\ApiResponse;
use App\Modules\Families\Presentation\Resources\FamilyResource;
use App\Modules\Families\Presentation\Http\Requests\{
    StoreFamilyRequest,
    UpdateFamilyRequest,
};
use App\Modules\Families\Application\UseCases\{
    CreateFamilyUseCase,
    DeleteFamilyUseCase,
    ListFamiliesUseCase,
    ShowFamilyUseCase,
    UpdateFamilyUseCase,
    FamilyExportUseCase
};
use App\Modules\Families\Application\DTOs\FamilyInput;
use App\Modules\Families\Domain\Models\Family;
use App\Modules\Subcategories\Domain\Models\Subcategory;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\Request;
use App\Modules\Families\Domain\Repositories\FamilyRepositoryInterface;
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

    public function index(int $subcategory, $supplierId = null)
    {
        $supplierId = (auth()->user()->company->supplier->id) ? auth()->user()->company->supplier->id :  $supplierId ;
        $families = $this->listFamilies->handle($subcategory, $supplierId);

        return ApiResponse::success(
            FamilyResource::collection($families)->resolve()
        );
    }

    public function store(StoreFamilyRequest $request)
    {

        $input = FamilyInput::fromArray(
            $request->all() + [
                'supplier_id' => auth()->user()->company->supplier->id,
                ]
            );

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
        $input = FamilyInput::fromArray(
            $request->all() + [
                'supplier_id' => auth()->user()->company->supplier->id,
            ]
        );
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

    public function export()
    {
        return Excel::download(new FamilyExportUseCase, 'families_template.xlsx');
    }

    public function import(Request $request,Subcategory $subcategory, FamilyRepositoryInterface $repo)
    {
        $supplier_id = auth()->user()->company->supplier->id;
        Excel::import(
            new FamilyImportUseCase(
                $supplier_id,
                $subcategory->id,
                $repo
            ),
            $request->file('file')
        );

        return ApiResponse::message('Families imported successfully');
    }

    


}

