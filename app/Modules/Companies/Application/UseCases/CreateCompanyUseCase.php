<?php

namespace App\Modules\Companies\Application\UseCases;

use App\Modules\Companies\Application\DTOs\{CompanyData, CompanyInput};
use App\Modules\Companies\Domain\Repositories\CompanyRepositoryInterface;

class CreateCompanyUseCase
{
    public function __construct(
        private readonly CompanyRepositoryInterface $repository,
    ) {
    }

    public function handle(CompanyInput $input): CompanyData
    {
        $company = $this->repository->create(
            $input->attributes,
            $input->logo,
        );

        return CompanyData::fromModel($company);
    }
}
