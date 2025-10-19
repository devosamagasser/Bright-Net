<?php

namespace App\Modules\Subcategories\Application\UseCases;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use App\Modules\Subcategories\Domain\Repositories\SubcategoryRepositoryInterface;

class ListSubcategoriesUseCase
{
    public function __construct(
        private readonly SubcategoryRepositoryInterface $repository,
    ) {
    }

    public function handle(int $perPage = 15, ?int $departmentId = null): LengthAwarePaginator
    {
        return $this->repository->paginate($perPage, $departmentId);
    }
}
