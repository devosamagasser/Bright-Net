<?php

namespace App\Modules\Families\Application\DTOs;

use Illuminate\Support\Collection;
use App\Modules\Families\Domain\Models\Family;

class FamilyData
{
    /**
     * @param  array<int, array<string, mixed>>  $translations
     * @param  array<int, array<string, mixed>>  $values
     * @param  array<int, array<string, mixed>>  $images
     */
    private function __construct(
        public readonly int $id,
        public readonly int $subcategoryId,
        public readonly int $supplierId,
        public readonly int $dataTemplateId,
        public readonly string $name,
        public readonly ?string $description,
        public readonly array $translations,
        public readonly array $values,
        public readonly array $images,
        public readonly string $createdAt,
        public readonly string $updatedAt,
    ) {
    }

    public static function fromModel(Family $family): self
    {
        $family->loadMissing(['fieldValues.field.translations', 'media']);

        $translations = $family->translations->map(static fn ($translation) => [
            'locale' => $translation->locale,
            'name' => $translation->name,
            'description' => $translation->description,
        ])->all();

        $values = $family->fieldValues
            ->sortBy(fn ($value) => $value->field->position ?? PHP_INT_MAX)
            ->map(static fn ($value) => FamilyValueData::fromModel($value)->toArray())
            ->values()
            ->all();

        $images = $family->getMedia('images')->map(static fn ($media) => [
            'id' => $media->getKey(),
            'url' => $media->getFullUrl(),
            'file_name' => $media->file_name,
        ])->all();

        return new self(
            id: $family->getKey(),
            subcategoryId: (int) $family->subcategory_id,
            supplierId: (int) $family->supplier_id,
            dataTemplateId: (int) $family->data_template_id,
            name: (string) $family->name,
            description: $family->description,
            translations: $translations,
            values: $values,
            images: $images,
            createdAt: $family->created_at?->toISOString() ?? '',
            updatedAt: $family->updated_at?->toISOString() ?? '',
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
