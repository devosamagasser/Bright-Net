<?php

namespace App\Modules\Companies\Domain\Repositories;

use Illuminate\Http\UploadedFile;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use App\Modules\Companies\Domain\Models\Company;
use App\Modules\Companies\Domain\ValueObjects\CompanyType;

interface CompanyRepositoryInterface
{
    public function paginateByType(CompanyType $type, int $perPage = 15, array $relations = []): LengthAwarePaginator;

    public function create(array $attributes): Company;

    public function update(Company $company, array $attributes): Company;

    public function syncLogo(Company $company, ?UploadedFile $logo = null): void;
}
