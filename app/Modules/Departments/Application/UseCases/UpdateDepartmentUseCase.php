<?php

namespace App\Modules\Departments\Application\UseCases;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Modules\Departments\Application\DTOs\{DepartmentData, DepartmentInput};
use App\Modules\Departments\Domain\Repositories\DepartmentRepositoryInterface;

class UpdateDepartmentUseCase
{
    public function __construct(
        private readonly DepartmentRepositoryInterface $repository,
    ) {
    }

    public function handle(int $departmentId, DepartmentInput $input): DepartmentData
    {
        $department = $this->repository->find($departmentId);

        if (! $department) {
            throw new ModelNotFoundException();
        }

        $department = $this->repository->update(
            $department,
            $input->attributes,
            $input->translations,
        );

        return DepartmentData::fromModel($department->load('subcategories'));
    }
}
