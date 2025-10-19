<?php

namespace App\Modules\Departments\Presentation\Http\Controllers;

use Illuminate\Http\{Request, Response};
use App\Modules\Shared\Support\Helper\ApiResponse;
use App\Modules\Departments\Application\DTOs\DepartmentInput;
use App\Modules\Departments\Presentation\Resources\DepartmentResource;
use App\Modules\Departments\Presentation\Http\Requests\{StoreDepartmentRequest, UpdateDepartmentRequest};
use App\Modules\Departments\Application\UseCases\{CreateDepartmentUseCase, DeleteDepartmentUseCase, ListDepartmentsUseCase, ShowDepartmentUseCase, UpdateDepartmentUseCase};

class DepartmentController
{
    public function __construct(
        private readonly ListDepartmentsUseCase $listDepartments,
        private readonly ShowDepartmentUseCase $showDepartment,
        private readonly CreateDepartmentUseCase $createDepartment,
        private readonly UpdateDepartmentUseCase $updateDepartment,
        private readonly DeleteDepartmentUseCase $deleteDepartment,
    ) {
    }

    public function index(Request $request)
    {
        $perPage = (int) $request->query('per_page', 15);
        $perPage = max(1, min(100, $perPage));
        $solutionId = $request->query('solution_id');
        $solutionId = $solutionId !== null ? (int) $solutionId : null;

        $paginator = $this->listDepartments->handle($perPage, $solutionId);

        return ApiResponse::success(
            DepartmentResource::collection($paginator)->resource
        );
    }

    public function store(StoreDepartmentRequest $request)
    {
        $input = DepartmentInput::fromArray($request->validated());
        $department = $this->createDepartment->handle($input);

        return ApiResponse::success(
            DepartmentResource::make($department),
            __('apiMessages.created'),
            Response::HTTP_CREATED
        );
    }

    public function show(int $department)
    {
        $dto = $this->showDepartment->handle($department);

        return ApiResponse::success(DepartmentResource::make($dto));
    }

    public function update(UpdateDepartmentRequest $request, int $department)
    {
        $input = DepartmentInput::fromArray($request->validated());
        $dto = $this->updateDepartment->handle($department, $input);

        return ApiResponse::success(
            DepartmentResource::make($dto),
            __('apiMessages.updated')
        );
    }

    public function destroy(int $department)
    {
        $this->deleteDepartment->handle($department);

        return ApiResponse::deleted();
    }
}
