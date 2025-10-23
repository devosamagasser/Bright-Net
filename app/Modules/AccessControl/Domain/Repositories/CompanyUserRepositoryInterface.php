<?php

namespace App\Modules\AccessControl\Domain\Repositories;

use App\Modules\AccessControl\Domain\Models\CompanyUser;
use App\Modules\Companies\Domain\ValueObjects\CompanyType;

interface CompanyUserRepositoryInterface
{
    public function findByEmailAndCompanyType(string $email, CompanyType $companyType): ?CompanyUser;
}
