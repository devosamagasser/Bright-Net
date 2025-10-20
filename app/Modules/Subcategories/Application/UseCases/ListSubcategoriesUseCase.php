<?php

namespace App\Modules\Subcategories\Application\UseCases;

use App\Modules\Subcategories\Application\DTOs\SubcategoryData;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use App\Modules\Subcategories\Domain\Repositories\SubcategoryRepositoryInterface;

class ListSubcategoriesUseCase
{
    public function __construct(
        private readonly SubcategoryRepositoryInterface $repository,
    ) {
    }

    public function handle(int $perPage = 15, int $departmentId): LengthAwarePaginator
    {
        $paginator = $this->repository->paginate($perPage, $departmentId);

        $paginator->setCollection(
            SubcategoryData::collection($paginator->getCollection())
        );

        return $paginator;
    }
}
