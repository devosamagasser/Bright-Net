<?php

namespace App\Modules\Companies\Application\UseCases;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Modules\Companies\Application\DTOs\{CompanyData, CompanyInput};
use App\Modules\Companies\Domain\Repositories\CompanyRepositoryInterface;

class UpdateCompanyUseCase
{
    public function __construct(
        private readonly CompanyRepositoryInterface $repository,
    ) {
    }

    public function handle(int $companyId, CompanyInput $input): CompanyData
    {
        $company = $this->repository->find($companyId);

        if (! $company) {
            throw new ModelNotFoundException();
        }

        $company = $this->repository->update(
            $company,
            $input->attributes,
            $input->logo,
        );

        return CompanyData::fromModel($company);
    }
}
