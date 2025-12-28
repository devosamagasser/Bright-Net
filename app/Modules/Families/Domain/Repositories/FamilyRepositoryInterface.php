<?php

namespace App\Modules\Families\Domain\Repositories;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Collection;
use App\Modules\Families\Domain\Models\Family;

interface FamilyRepositoryInterface
{
    /**
     * @param  array<string, mixed>  $attributes
     * @param  array<string, array<string, mixed>>  $translations
     * @param  array<string, mixed>  $values
     */
    public function create(array $attributes, array $translations, array $values, ?UploadedFile $image = null): Family;

    /**
     * @param  array<string, mixed>  $attributes
     * @param  array<string, array<string, mixed>>  $translations
     * @param  array<string, mixed>  $values
     */
    public function update(Family $family, array $attributes, array $translations, array $values, ?UploadedFile $image = null): Family;

    public function delete(Family $family): void;

    public function find(int $id): ?Family;

    public function changeOrder(Family $family, Family $familyBefore);
    /**
     * @return Collection<int, Family>
     */
    public function getBySubcategory(int $subcategoryId, ?int $supplierId = null): Collection;
    public function getBySubcategoryAndSupplierDepartment(int $subcategoryId, int $supplierDepartmentId, ?int $supplierId = null): Collection;
}
