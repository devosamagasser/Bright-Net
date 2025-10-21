<?php

namespace App\Modules\Companies\Application\UseCases;

use Illuminate\Support\Facades\DB;
use App\Modules\Companies\Domain\Models\Company;
use App\Modules\Companies\Application\DTOs\CompanyData;
use App\Modules\Companies\Application\DTOs\CompanyInput;
use App\Modules\Companies\Domain\Profiles\CompanyProfileFactory;
use App\Modules\Companies\Domain\Repositories\CompanyRepositoryInterface;
use App\Modules\Companies\Domain\ValueObjects\CompanyType;
use InvalidArgumentException;

class UpdateCompanyUseCase
{
    public function __construct(
        private readonly CompanyRepositoryInterface $repository,
        private readonly CompanyProfileFactory $profiles,
    ) {
    }

    public function handle(Company $company, CompanyInput $input): CompanyData
    {
        $this->guardCompanyType($company, $input->type);

        $profile = $this->profiles->make($input->type);

        return DB::transaction(function () use ($company, $input, $profile) {
            $this->repository->update($company, $input->attributes);
            $this->repository->syncLogo($company, $input->logo);

            $profile->update($company, $input->profilePayload);

            $company->load($profile->relations());

            return CompanyData::fromModel($company, $profile);
        });
    }

    private function guardCompanyType(Company $company, CompanyType $type): void
    {
        if ($company->type !== $type) {
            throw new InvalidArgumentException('Company type mismatch for update operation.');
        }
    }
}
