<?php

namespace App\Modules\Companies\Application\DTOs;

use Illuminate\Support\Collection;
use App\Modules\Companies\Domain\Models\Company;
use App\Modules\Companies\Domain\Profiles\CompanyProfileInterface;

class CompanyData
{
    /**
     * @param  array<string, mixed>  $profile
     */
    private function __construct(
        public readonly int $id,
        public readonly string $name,
        public readonly ?string $description,
        public readonly string $type,
        public readonly ?string $logo,
        public readonly array $profile,
        public readonly string $createdAt,
        public readonly string $updatedAt,
    ) {
    }

    public static function fromModel(Company $company, CompanyProfileInterface $profile): self
    {
        return new self(
            id: $company->getKey(),
            name: $company->name,
            description: $company->description,
            type: $company->type->value,
            logo: $company->getFirstMediaUrl('logo') ?: null,
            profile: $profile->serialize($company),
            createdAt: $company->created_at?->toISOString() ?? '',
            updatedAt: $company->updated_at?->toISOString() ?? '',
        );
    }

    /**
     * @param  Collection<int, Company>  $companies
     * @return Collection<int, self>
     */
    public static function collection(Collection $companies, CompanyProfileInterface $profile): Collection
    {
        return $companies->map(static fn (Company $company) => self::fromModel($company, $profile));
    }
}
