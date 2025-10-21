<?php

namespace App\Modules\Brands\Application\UseCases;

use App\Modules\Brands\Application\DTOs\BrandData;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use App\Modules\Brands\Domain\Repositories\BrandRepositoryInterface;

class ListBrandsUseCase
{
    public function __construct(
        private readonly BrandRepositoryInterface $repository,
    ) {
    }

    public function handle(int $perPage = 15, array $filter = []): LengthAwarePaginator
    {
        $paginator = $this->repository->paginate($perPage, $filter);

        $paginator->setCollection(
            BrandData::collection($paginator->getCollection())
        );

        return $paginator;
    }
}
