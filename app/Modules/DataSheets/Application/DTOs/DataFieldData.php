<?php

namespace App\Modules\DataSheets\Application\DTOs;

use App\Modules\DataSheets\Domain\Models\DataField;
use App\Modules\DataSheets\Domain\ValueObjects\DataFieldType;

class DataFieldData
{
    /**
     * @param  array<string, mixed>  $attributes
     * @param  array<int, array<string, mixed>>  $translations
     */
    private function __construct(
        public readonly array $attributes,
        public readonly array $translations,
    ) {
    }

    public static function fromModel(DataField $field): self
    {
        return new self(
            attributes: [
                'id' => $field->getKey(),
                'slug' => $field->slug,
                'type' => $field->type instanceof DataFieldType ? $field->type->value : $field->type,
                'is_required' => $field->is_required,
                'is_filterable' => $field->is_filterable,
                'options' => $field->options,
                'position' => $field->position,
            ],
            translations: $field->translations->map(function ($translation) {
                return [
                    'locale' => $translation->locale,
                    'label' => $translation->label,
                    'placeholder' => $translation->placeholder,
                ];
            })->all(),
        );
    }
}
