<?php

namespace App\Modules\Departments\Application\UseCases;

use App\Modules\Departments\Application\DTOs\{DepartmentData, DepartmentInput};
use App\Modules\Departments\Domain\Repositories\DepartmentRepositoryInterface;

class CreateDepartmentUseCase
{
    public function __construct(
        private readonly DepartmentRepositoryInterface $repository,
    ) {
    }

    public function handle(DepartmentInput $input): DepartmentData
    {
        $department = $this->repository->create(
            attributes: $input->attributes,
            translations: $input->translations,
        );

        return DepartmentData::fromModel($department->load('subcategories'));
    }
}
