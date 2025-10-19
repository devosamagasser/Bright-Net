<?php

namespace App\Modules\Subcategories\Domain\Repositories;

use App\Models\Subcategory;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface SubcategoryRepositoryInterface
{
    public function paginate(int $perPage = 15, ?int $departmentId = null): LengthAwarePaginator;

    public function find(int $id): ?Subcategory;

    /**
     * @param  array<string, mixed>  $attributes
     * @param  array<string, array<string, mixed>>  $translations
     */
    public function create(array $attributes, array $translations): Subcategory;

    /**
     * @param  array<string, mixed>  $attributes
     * @param  array<string, array<string, mixed>>  $translations
     */
    public function update(Subcategory $subcategory, array $attributes, array $translations): Subcategory;

    public function delete(Subcategory $subcategory): void;
}
