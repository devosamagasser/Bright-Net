<?php

namespace App\Modules\Favourites\Application\UseCases;

use App\Modules\Favourites\Application\DTOs\CollectionData;
use App\Modules\Favourites\Domain\Repositories\CollectionRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class ListCollectionsUseCase
{
    public function __construct(
        private readonly CollectionRepositoryInterface $repository,
    ) {
    }

    public function handle(int $companyId, int $perPage = 15): LengthAwarePaginator
    {
        $paginator = $this->repository->getByCompany($companyId, $perPage);

        return $paginator->setCollection(
            CollectionData::collection($paginator->getCollection())
        );
    }
}

