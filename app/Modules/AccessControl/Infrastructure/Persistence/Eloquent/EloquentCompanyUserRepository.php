<?php

namespace App\Modules\AccessControl\Infrastructure\Persistence\Eloquent;

use App\Modules\AccessControl\Domain\Models\CompanyUser;
use App\Modules\AccessControl\Domain\Repositories\CompanyUserRepositoryInterface;
use App\Modules\Companies\Domain\ValueObjects\CompanyType;

final class EloquentCompanyUserRepository implements CompanyUserRepositoryInterface
{
    public function findByEmailAndCompanyType(string $email, CompanyType $companyType): ?CompanyUser
    {
        return CompanyUser::query()
            ->where('email', $email)
            ->whereHas('company', fn ($query) => $query->where('type', $companyType->value))
            ->with('company')
            ->first();
    }
}
