<?php

namespace App\Modules\Companies\Application\UseCases;

use App\Modules\Companies\Domain\Models\Company;
use App\Modules\Companies\Application\DTOs\CompanyData;
use App\Modules\Companies\Domain\Profiles\CompanyProfileFactory;
use App\Modules\Companies\Domain\ValueObjects\CompanyType;
use InvalidArgumentException;

class ShowCompanyUseCase
{
    public function __construct(
        private readonly CompanyProfileFactory $profiles,
    ) {
    }

    public function handle(Company $company, CompanyType $type): CompanyData
    {
        if ($company->type !== $type) {
            throw new InvalidArgumentException('Requested company does not match the expected type.');
        }

        $profile = $this->profiles->make($type);

        $company->load($profile->relations());

        return CompanyData::fromModel($company, $profile);
    }
}
