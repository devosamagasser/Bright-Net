<?php

namespace App\Modules\Companies\Application\UseCases;

use App\Modules\Companies\Application\DTOs\CompanyData;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use App\Modules\Companies\Domain\Repositories\CompanyRepositoryInterface;

class ListCompaniesUseCase
{
    public function __construct(
        private readonly CompanyRepositoryInterface $repository,
    ) {
    }

    public function handle(int $perPage = 15, array $filter = []): LengthAwarePaginator
    {
        $paginator = $this->repository->paginate($perPage, $filter);

        $paginator->setCollection(
            CompanyData::collection($paginator->getCollection())
        );

        return $paginator;
    }
}
