<?php

namespace App\Modules\Companies\Application\UseCases;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Modules\Companies\Domain\Repositories\CompanyRepositoryInterface;

class DeleteCompanyUseCase
{
    public function __construct(
        private readonly CompanyRepositoryInterface $repository,
    ) {
    }

    public function handle(int $companyId): void
    {
        $company = $this->repository->find($companyId);

        if (! $company) {
            throw new ModelNotFoundException();
        }

        $this->repository->delete($company);
    }
}
