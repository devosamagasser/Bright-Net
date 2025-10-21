<?php

namespace App\Modules\Companies\Domain\Profiles;

use App\Modules\Companies\Domain\Models\Company;
use App\Modules\Companies\Domain\ValueObjects\CompanyType;

interface CompanyProfileInterface
{
    public function type(): CompanyType;

    /**
     * Return the relationships required to hydrate this profile.
     *
     * @return array<int, string>
     */
    public function relations(): array;

    /**
     * Persist profile specific data when a company is created.
     *
     * @param  array<string, mixed>  $payload
     */
    public function create(Company $company, array $payload): void;

    /**
     * Persist profile specific data when a company is updated.
     *
     * @param  array<string, mixed>  $payload
     */
    public function update(Company $company, array $payload): void;

    /**
     * Transform the profile specific data for presentation.
     *
     * @return array<string, mixed>
     */
    public function serialize(Company $company): array;
}
