<?php

namespace App\Modules\Companies\Infrastructure\Persistence\Eloquent;

use App\Modules\Companies\Domain\Models\Company;
use Illuminate\Http\UploadedFile;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use App\Modules\Companies\Domain\Repositories\CompanyRepositoryInterface;
use App\Modules\Companies\Domain\ValueObjects\CompanyType;

class EloquentCompanyRepository implements CompanyRepositoryInterface
{
    public function paginateByType(CompanyType $type, int $perPage = 15, array $relations = []): LengthAwarePaginator
    {
        return Company::query()
            ->with($relations)
            ->where('type', $type->value)
            ->orderBy('name')
            ->paginate($perPage);
    }

    public function create(array $attributes): Company
    {
        return Company::create($attributes);
    }

    public function update(Company $company, array $attributes): Company
    {
        $company->fill($attributes);
        $company->save();

        return $company;
    }

    public function syncLogo(Company $company, ?UploadedFile $logo = null): void
    {
        if ($logo === null) {
            return;
        }

        $company->addMedia($logo)
            ->toMediaCollection('logo');
    }

    public function delete(Company $company): void
    {
        $company->delete();
    }
}
