<?php

namespace App\Modules\SolutionsCatalog\Application\DTOs;

use App\Modules\SolutionsCatalog\Domain\Models\Solution;
use Illuminate\Support\Collection;

class SolutionData
{
    /**
     * @param  array<string, string>  $name
     * @param  array<int, array<string, mixed>>  $departments
     */
    private function __construct(
        public readonly int $id,
        public readonly string $name,
        public readonly ?Collection $departments = null,
        public readonly array $translations,
        public readonly string $createdAt,
        public readonly string $updatedAt,
    ) {
    }

    /**
     * Build a DTO instance from the Eloquent model.
     */
    public static function fromModel(Solution $solution): self
    {
        return new self(
            id: $solution->getKey(),
            name: $solution->name,
            departments: $solution->relationLoaded('departments')
                ? $solution->departments
                : null,
            translations: $solution->relationLoaded('departments') ? $solution->translations
                ->mapWithKeys(static fn ($translation) => [
                    $translation->locale => ['name' => $translation->name],
                ])->toArray() : [],
            createdAt: $solution->created_at?->toISOString() ?? '',
            updatedAt: $solution->updated_at?->toISOString() ?? '',
        );
    }

    /**
     * Transform a collection of models into DTOs.
     *
     * @param  \Illuminate\Support\Collection<int, Solution>  $solutions
     * @return \Illuminate\Support\Collection<int, self>
     */
    public static function collection(Collection $solutions): Collection
    {
        return $solutions->map(fn (Solution $solution) => self::fromModel($solution));
    }
}
