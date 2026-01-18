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
            attributes: self::fieldAttributes($field),
            translations: $field->translations->map(function ($translation) {
                return [
                    'locale' => $translation->locale,
                    'label' => $translation->label,
                    'placeholder' => $translation->placeholder,
                ];
            })->all(),
        );
    }

    public static function fieldAttributes($field): array
    {
        return [
            'id' => $field->getKey(),
            'group' => $field->group,
            'label' => $field->label,
            'placeholder' => $field->placeholder,
            'name' => $field->name,
            'type' => $field->type instanceof DataFieldType ? $field->type->value : $field->type,
            'is_required' => $field->is_required,
            'is_filterable' => $field->is_filterable,
            'with_custom' => (bool) $field->with_custom,
            'prefix' => $field->prefix,
            'suffix' => $field->suffix,
            'options' => $field->options,
            'position' => $field->position,
            'is_depended' => $field->dependency !== null,
            'depends_on_field' => $field->dependency?->dependsOnField?->name,
            'depends_on_values' => $field->dependency?->values ?? [],
        ];
    }
}
