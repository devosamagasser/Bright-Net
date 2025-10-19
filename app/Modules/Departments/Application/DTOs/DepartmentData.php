<?php

namespace App\Modules\Departments\Application\DTOs;

use App\Models\Department;
use Illuminate\Support\Collection;

class DepartmentData
{
    /**
     * @param  array<int, array<string, mixed>>  $subcategories
     * @param  array<string, array<string, mixed>>  $translations
     */
    private function __construct(
        public readonly int $id,
        public readonly int $solutionId,
        public readonly string $name,
        public readonly array $subcategories,
        public readonly array $translations,
        public readonly string $createdAt,
        public readonly string $updatedAt,
    ) {
    }

    public static function fromModel(Department $department): self
    {
        return new self(
            id: $department->getKey(),
            solutionId: (int) $department->solution_id,
            name: $department->name,
            subcategories: $department->subcategories
                ->map(static fn ($subcategory) => [
                    'id' => $subcategory->getKey(),
                    'name' => $subcategory->name,
                    'department_id' => (int) $subcategory->department_id,
                    'translations' => $subcategory->translations
                        ->mapWithKeys(static fn ($translation) => [
                            $translation->locale => ['name' => $translation->name],
                        ])->toArray(),
                ])->toArray(),
            translations: $department->translations
                ->mapWithKeys(static fn ($translation) => [
                    $translation->locale => ['name' => $translation->name],
                ])->toArray(),
            createdAt: $department->created_at?->toISOString() ?? '',
            updatedAt: $department->updated_at?->toISOString() ?? '',
        );
    }

    /**
     * @param  Collection<int, Department>  $departments
     * @return Collection<int, self>
     */
    public static function collection(Collection $departments): Collection
    {
        return $departments->map(static fn (Department $department) => self::fromModel($department));
    }
}
