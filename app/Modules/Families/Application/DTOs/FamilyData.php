<?php

namespace App\Modules\Families\Application\DTOs;

use Illuminate\Support\Collection;
use App\Modules\Families\Domain\Models\Family;

class FamilyData
{
    /**
     * @param  array<string, mixed>  $attributes
     * @param  array<string, array<string, mixed>>  $translations
     * @param  array<int, FamilyValueData>  $values
     */
    private function __construct(
        public readonly array $attributes,
        public readonly array $translations,
        public readonly array $values,
    ) {
    }

    public static function fromModel(Family $family): self
    {
        $family->loadMissing(
            'translations',
            'fieldValues.field.translations',
            'fieldValues.field.dependencies.dependsOnField'
        );

        return new self(
            attributes: [
                'id' => (int) $family->getKey(),
                'subcategory_id' => (int) $family->subcategory_id,
                'supplier_id' => (int) $family->supplier_id,
                'data_template_id' => (int) $family->data_template_id,
                'name' => $family->name,
                'created_at' => $family->created_at?->toISOString(),
                'updated_at' => $family->updated_at?->toISOString(),
            ],
            translations: $family->translations
                ->mapWithKeys(static fn ($translation) => [
                    $translation->locale => [
                        'description' => $translation->description,
                    ],
                ])->toArray(),
            values: $family->fieldValues
                ->sortBy(static fn ($value) => $value->field?->position ?? 0)
                ->map(static fn ($value) => FamilyValueData::fromModel($value))
                ->values()
                ->all(),
        );
    }

    /**
     * @param  Collection<int, Family>  $families
     * @return Collection<int, self>
     */
    public static function collection(Collection $families): Collection
    {
        return $families->map(static fn (Family $family) => self::fromModel($family));
    }
}
