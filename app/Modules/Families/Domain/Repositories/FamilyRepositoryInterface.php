<?php

namespace App\Modules\Families\Domain\Repositories;

use App\Modules\Families\Domain\Models\Family;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface FamilyRepositoryInterface
{
    /**
     * Paginate families within a subcategory (optionally scoped to a supplier).
     */
    public function paginate(int $perPage, int $subcategoryId, ?int $supplierId = null): LengthAwarePaginator;

    public function find(int $id): ?Family;

    /**
     * @param  array<string, mixed>  $attributes
     * @param  array<string, array<string, mixed>>  $translations
     * @param  array<int, array{data_field_id:int, value:mixed}>  $values
     */
    public function create(array $attributes, array $translations, array $values): Family;

    /**
     * @param  array<string, mixed>  $attributes
     * @param  array<string, array<string, mixed>>  $translations
     * @param  array<int, array{data_field_id:int, value:mixed}>|null  $values
     */
    public function update(Family $family, array $attributes, array $translations, ?array $values = null): Family;

    public function delete(Family $family): void;
}
