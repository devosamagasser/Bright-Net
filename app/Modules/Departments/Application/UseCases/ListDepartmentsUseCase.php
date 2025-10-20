<?php

namespace App\Modules\Departments\Application\UseCases;

use App\Modules\Departments\Application\DTOs\DepartmentData;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use App\Modules\Departments\Domain\Repositories\DepartmentRepositoryInterface;

class ListDepartmentsUseCase
{
    public function __construct(
        private readonly DepartmentRepositoryInterface $repository,
    ) {
    }

    public function handle(int $perPage = 15, int $solutionId): LengthAwarePaginator
    {

        $paginator = $this->repository->paginate($perPage, $solutionId);

        $paginator->setCollection(
            DepartmentData::collection($paginator->getCollection())
        );

        return $paginator;
    }
}
