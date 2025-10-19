<?php

namespace App\Modules\Geography\Application\UseCases;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use App\Modules\Geography\Domain\Repositories\RegionRepositoryInterface;
use App\Modules\Geography\Application\DTOs\RegionData;

class ListRegionsUseCase
{
    public function __construct(
        private readonly RegionRepositoryInterface $repository,
    ) {
    }

    public function handle(int $perPage = 15): LengthAwarePaginator
    {
        $paginator = $this->repository->paginate($perPage);

        $paginator->setCollection(
            RegionData::collection($paginator->getCollection())
        );

        return $paginator;
    }
}
