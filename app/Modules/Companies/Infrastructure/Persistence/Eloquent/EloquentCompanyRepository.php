<?php

namespace App\Modules\Companies\Infrastructure\Persistence\Eloquent;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\UploadedFile;
use Illuminate\Database\Eloquent\Builder;
use App\Modules\Companies\Domain\Models\Company;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use App\Modules\Companies\Domain\Repositories\CompanyRepositoryInterface;

class EloquentCompanyRepository implements CompanyRepositoryInterface
{
    public function paginate(int $perPage = 15, array $filter = []): LengthAwarePaginator
    {
        return $this->query()
            ->filter($filter)
            ->orderByDesc('id')
            ->paginate($perPage);
    }

    public function find(int $id): ?Company
    {
        return $this->query()->find($id);
    }

    public function create(array $attributes, ?UploadedFile $logo = null): Company
    {
        $company = new Company();

        return $this->fillCompany($company, $attributes, $logo);
    }

    public function update(Company $company, array $attributes, ?UploadedFile $logo = null): Company
    {
        return $this->fillCompany($company, $attributes, $logo);
    }

    public function delete(Company $company): void
    {
        $company->delete();
    }

    protected function query(): Builder
    {
        return Company::query();
    }

    /**
     * Persist shared logic for create & update operations.
     *
     * @param  array<string, mixed>  $attributes
     */
    protected function fillCompany(Company $company, array $attributes, ?UploadedFile $logo = null): Company
    {
        return DB::transaction(function () use ($company, $attributes, $logo) {
            $company->fill($attributes);
            $company->save();

            if ($logo) {
                $company->addMedia($logo)->toMediaCollection('logo');
            }

            return $company->refresh();
        });
    }
}
