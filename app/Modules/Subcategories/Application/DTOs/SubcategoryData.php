<?php

namespace App\Modules\Subcategories\Application\DTOs;

use App\Models\Subcategory;
use Illuminate\Support\Collection;

class SubcategoryData
{
    /**
     * @param  array<string, array<string, mixed>>  $translations
     */
    private function __construct(
        public readonly int $id,
        public readonly int $departmentId,
        public readonly string $name,
        public readonly array $translations,
        public readonly string $createdAt,
        public readonly string $updatedAt,
    ) {
    }

    public static function fromModel(Subcategory $subcategory): self
    {
        return new self(
            id: $subcategory->getKey(),
            departmentId: (int) $subcategory->department_id,
            name: $subcategory->name,
            translations: $subcategory->translations
                ->mapWithKeys(static fn ($translation) => [
                    $translation->locale => ['name' => $translation->name],
                ])->toArray(),
            createdAt: $subcategory->created_at?->toISOString() ?? '',
            updatedAt: $subcategory->updated_at?->toISOString() ?? '',
        );
    }

    /**
     * @param  Collection<int, Subcategory>  $subcategories
     * @return Collection<int, self>
     */
    public static function collection(Collection $subcategories): Collection
    {
        return $subcategories->map(static fn (Subcategory $subcategory) => self::fromModel($subcategory));
    }
}
