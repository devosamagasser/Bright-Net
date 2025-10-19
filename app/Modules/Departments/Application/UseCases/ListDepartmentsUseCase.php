<?php

namespace App\Modules\Departments\Application\UseCases;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use App\Modules\Departments\Domain\Repositories\DepartmentRepositoryInterface;

class ListDepartmentsUseCase
{
    public function __construct(
        private readonly DepartmentRepositoryInterface $repository,
    ) {
    }

    public function handle(int $perPage = 15, ?int $solutionId = null): LengthAwarePaginator
    {
        return $this->repository->paginate($perPage, $solutionId);
    }
}
