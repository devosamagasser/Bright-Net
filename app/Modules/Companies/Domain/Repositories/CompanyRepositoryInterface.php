<?php

namespace App\Modules\Companies\Domain\Repositories;

use Illuminate\Http\UploadedFile;
use App\Modules\Companies\Domain\Models\Company;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface CompanyRepositoryInterface
{
    /**
     * Paginate available companies.
     */
    public function paginate(int $perPage = 15, array $filter = []): LengthAwarePaginator;

    /**
     * Retrieve a company by its primary key.
     */
    public function find(int $id): ?Company;

    /**
     * Persist a newly created company.
     *
     * @param  array<string, mixed>  $attributes
     */
    public function create(array $attributes, ?UploadedFile $logo = null): Company;

    /**
     * Update an existing company.
     *
     * @param  array<string, mixed>  $attributes
     */
    public function update(Company $company, array $attributes, ?UploadedFile $logo = null): Company;

    /**
     * Delete the provided company instance.
     */
    public function delete(Company $company): void;
}
