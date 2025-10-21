<?php

namespace App\Modules\Companies\Application\UseCases;

use Illuminate\Support\Facades\DB;
use App\Modules\Companies\Domain\Models\Company;
use App\Modules\Companies\Domain\Repositories\CompanyRepositoryInterface;
use App\Modules\Companies\Domain\ValueObjects\CompanyType;
use InvalidArgumentException;

class DeleteCompanyUseCase
{
    public function __construct(
        private readonly CompanyRepositoryInterface $repository,
    ) {
    }

    public function handle(Company $company, CompanyType $type): void
    {
        if ($company->type !== $type) {
            throw new InvalidArgumentException('Company type mismatch for delete operation.');
        }

        DB::transaction(function () use ($company): void {
            $this->repository->delete($company);
        });
    }
}
