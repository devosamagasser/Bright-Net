<?php

namespace App\Modules\Companies\Application\UseCases;

use Illuminate\Support\Facades\DB;
use App\Modules\Companies\Application\DTOs\CompanyData;
use App\Modules\Companies\Application\DTOs\CompanyInput;
use App\Modules\Companies\Domain\Profiles\CompanyProfileFactory;
use App\Modules\Companies\Domain\Repositories\CompanyRepositoryInterface;

class CreateCompanyUseCase
{
    public function __construct(
        private readonly CompanyRepositoryInterface $repository,
        private readonly CompanyProfileFactory $profiles,
    ) {
    }

    public function handle(CompanyInput $input): CompanyData
    {
        $profile = $this->profiles->make($input->type);

        return DB::transaction(function () use ($input, $profile) {
            $company = $this->repository->create($input->attributes + [
                'type' => $input->type->value,
            ]);

            $this->repository->syncLogo($company, $input->logo);

            $profile->create($company, $input->profilePayload);

            $company->load($profile->relations());

            return CompanyData::fromModel($company, $profile);
        });
    }
}
