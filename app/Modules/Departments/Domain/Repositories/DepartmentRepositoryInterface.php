<?php

namespace App\Modules\Departments\Domain\Repositories;

use Illuminate\Http\UploadedFile;
use App\Modules\Departments\Domain\Models\Department;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface DepartmentRepositoryInterface
{
    /**
     * Paginate departments optionally filtered by solution.
     */
    public function paginate(int $perPage = 15, int $solutionId): LengthAwarePaginator;

    /**
     * Retrieve a department by its primary key.
     */
    public function find(int $id): ?Department;

    /**
     * Create a new department with translations.
     *
     * @param  array<string, mixed>  $attributes
     * @param  array<string, array<string, mixed>>  $translations
     */
    public function create(array $attributes, array $translations, UploadedFile $cover): Department;

    /**
     * Update an existing department.
     *
     * @param  array<string, mixed>  $attributes
     * @param  array<string, array<string, mixed>>  $translations
     */
    public function update(Department $department, array $attributes, array $translations, ?UploadedFile $cover = null): Department;

    /**
     * Delete the given department.
     */
    public function delete(Department $department): void;
}
