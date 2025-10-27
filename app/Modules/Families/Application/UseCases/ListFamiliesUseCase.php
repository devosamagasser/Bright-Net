<?php

namespace App\Modules\Families\Application\UseCases;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use App\Modules\Families\Application\DTOs\FamilyData;
use App\Modules\Families\Domain\Repositories\FamilyRepositoryInterface;

class ListFamiliesUseCase
{
    public function __construct(
        private readonly FamilyRepositoryInterface $repository,
    ) {
    }

    public function handle(int $subcategoryId, int $perPage = 15, ?int $supplierId = null): LengthAwarePaginator
    {
        $paginator = $this->repository->paginate($perPage, $subcategoryId, $supplierId);

        $paginator->setCollection(
            FamilyData::collection($paginator->getCollection())
        );

        return $paginator;
    }
}
