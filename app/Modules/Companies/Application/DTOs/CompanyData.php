<?php

namespace App\Modules\Companies\Application\DTOs;

use Illuminate\Support\Collection;
use App\Modules\Companies\Domain\Models\Company;
use App\Modules\Companies\Domain\ValueObjects\CompanyType;

class CompanyData
{
    private function __construct(
        public readonly int $id,
        public readonly string $name,
        public readonly string $type,
        public readonly string $typeLabel,
        public readonly ?string $logo,
        public readonly string $createdAt,
        public readonly string $updatedAt,
    ) {
    }

    public static function fromModel(Company $company): self
    {
        $type = $company->type instanceof CompanyType
            ? $company->type
            : CompanyType::from($company->type);

        return new self(
            id: $company->getKey(),
            name: $company->name,
            type: $type->value,
            typeLabel: $type->label(),
            logo: $company->getFirstMediaUrl('logo') ?: null,
            createdAt: $company->created_at?->toISOString() ?? '',
            updatedAt: $company->updated_at?->toISOString() ?? '',
        );
    }

    /**
     * @param  Collection<int, Company>  $companies
     * @return Collection<int, self>
     */
    public static function collection(Collection $companies): Collection
    {
        return $companies->map(static fn (Company $company) => self::fromModel($company));
    }
}
