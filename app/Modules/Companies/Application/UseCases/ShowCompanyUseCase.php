<?php

namespace App\Modules\Companies\Application\UseCases;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Modules\Companies\Application\DTOs\CompanyData;
use App\Modules\Companies\Domain\Repositories\CompanyRepositoryInterface;

class ShowCompanyUseCase
{
    public function __construct(
        private readonly CompanyRepositoryInterface $repository,
    ) {
    }

    public function handle(int $companyId): CompanyData
    {
        $company = $this->repository->find($companyId);

        if (! $company) {
            throw new ModelNotFoundException();
        }

        return CompanyData::fromModel($company);
    }
}
