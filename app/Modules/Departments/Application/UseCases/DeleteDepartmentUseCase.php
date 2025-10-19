<?php

namespace App\Modules\Departments\Application\UseCases;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Modules\Departments\Domain\Repositories\DepartmentRepositoryInterface;

class DeleteDepartmentUseCase
{
    public function __construct(
        private readonly DepartmentRepositoryInterface $repository,
    ) {
    }

    public function handle(int $departmentId): void
    {
        $department = $this->repository->find($departmentId);

        if (! $department) {
            throw new ModelNotFoundException();
        }

        $this->repository->delete($department);
    }
}
