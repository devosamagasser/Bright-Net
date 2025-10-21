<?php

namespace App\Modules\Companies\Application\UseCases;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use App\Modules\Companies\Application\DTOs\CompanyData;
use App\Modules\Companies\Domain\Profiles\CompanyProfileFactory;
use App\Modules\Companies\Domain\Repositories\CompanyRepositoryInterface;
use App\Modules\Companies\Domain\ValueObjects\CompanyType;

class ListCompaniesByTypeUseCase
{
    public function __construct(
        private readonly CompanyRepositoryInterface $repository,
        private readonly CompanyProfileFactory $profiles,
    ) {
    }

    public function handle(CompanyType $type, int $perPage = 15): LengthAwarePaginator
    {
        $profile = $this->profiles->make($type);

        $paginator = $this->repository->paginateByType($type, $perPage, $profile->relations());

        $paginator->setCollection(
            CompanyData::collection($paginator->getCollection(), $profile)
        );

        return $paginator;
    }
}
