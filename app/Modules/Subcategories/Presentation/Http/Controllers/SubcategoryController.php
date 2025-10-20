<?php

namespace App\Modules\Subcategories\Presentation\Http\Controllers;

use Illuminate\Http\{Request, Response};
use App\Modules\Shared\Support\Helper\ApiResponse;
use App\Modules\Subcategories\Application\DTOs\SubcategoryInput;
use App\Modules\Subcategories\Presentation\Resources\SubcategoryResource;
use App\Modules\Subcategories\Presentation\Http\Requests\{StoreSubcategoryRequest, UpdateSubcategoryRequest};
use App\Modules\Subcategories\Application\UseCases\{CreateSubcategoryUseCase, DeleteSubcategoryUseCase, ListSubcategoriesUseCase, ShowSubcategoryUseCase, UpdateSubcategoryUseCase};

class SubcategoryController
{
    public function __construct(
        private readonly ListSubcategoriesUseCase $listSubcategories,
        private readonly ShowSubcategoryUseCase $showSubcategory,
        private readonly CreateSubcategoryUseCase $createSubcategory,
        private readonly UpdateSubcategoryUseCase $updateSubcategory,
        private readonly DeleteSubcategoryUseCase $deleteSubcategory,
    ) {
    }

    public function index(Request $request, $departmentId)
    {
        $perPage = (int) $request->query('per_page', 15);
        $perPage = max(1, min(100, $perPage));

        $paginator = $this->listSubcategories->handle($perPage, $departmentId);

        return ApiResponse::success(
            SubcategoryResource::collection($paginator)->resource
        );
    }

    public function store(StoreSubcategoryRequest $request)
    {
        $input = SubcategoryInput::fromArray($request->validated());
        $subcategory = $this->createSubcategory->handle($input);

        return ApiResponse::success(
            SubcategoryResource::make($subcategory),
            __('apiMessages.created'),
            Response::HTTP_CREATED
        );
    }

    public function show(int $subcategory)
    {
        $dto = $this->showSubcategory->handle($subcategory);

        return ApiResponse::success(SubcategoryResource::make($dto));
    }

    public function update(UpdateSubcategoryRequest $request, int $subcategory)
    {
        $input = SubcategoryInput::fromArray($request->validated());
        $dto = $this->updateSubcategory->handle($subcategory, $input);

        return ApiResponse::success(
            SubcategoryResource::make($dto),
            __('apiMessages.updated')
        );
    }

    public function destroy(int $subcategory)
    {
        $this->deleteSubcategory->handle($subcategory);

        return ApiResponse::deleted();
    }
}
