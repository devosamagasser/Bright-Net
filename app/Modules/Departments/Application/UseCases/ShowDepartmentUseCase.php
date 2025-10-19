<?php

namespace App\Modules\Departments\Application\UseCases;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Modules\Departments\Application\DTOs\DepartmentData;
use App\Modules\Departments\Domain\Repositories\DepartmentRepositoryInterface;

class ShowDepartmentUseCase
{
    public function __construct(
        private readonly DepartmentRepositoryInterface $repository,
    ) {
    }

    public function handle(int $departmentId): DepartmentData
    {
        $department = $this->repository->find($departmentId);

        if (! $department) {
            throw new ModelNotFoundException();
        }

        return DepartmentData::fromModel($department);
    }
}
