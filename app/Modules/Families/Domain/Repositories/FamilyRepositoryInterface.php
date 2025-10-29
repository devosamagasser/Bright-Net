<?php

namespace App\Modules\Families\Domain\Repositories;

use Illuminate\Support\Collection;
use App\Modules\Families\Domain\Models\Family;

interface FamilyRepositoryInterface
{
    /**
     * @param  array<string, mixed>  $attributes
     * @param  array<string, array<string, mixed>>  $translations
     * @param  array<string, mixed>  $values
     */
    public function create(array $attributes, array $translations, array $values): Family;

    /**
     * @param  array<string, mixed>  $attributes
     * @param  array<string, array<string, mixed>>  $translations
     * @param  array<string, mixed>  $values
     */
    public function update(Family $family, array $attributes, array $translations, array $values): Family;

    public function delete(Family $family): void;

    public function find(int $id): ?Family;

    /**
     * @return Collection<int, Family>
     */
    public function getBySubcategory(int $subcategoryId, ?int $supplierId = null): Collection;
}
