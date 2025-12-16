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
                'label' => $field->label,
                'placeholder' => $field->placeholder,
                'name' => $field->name,
                'type' => $field->type instanceof DataFieldType ? $field->type->value : $field->type,
                'is_required' => $field->is_required,
                'is_filterable' => $field->is_filterable,
                'options' => match ($field->type) {
                    'select' => collect($field->options ?? [])
                        ->map(fn ($option) => [
                            'label' => $option,
                            'value' => $option,
                        ])
                        ->values()
                        ->all(),

                    'groupedselect' => collect($field->options ?? [])
                        ->map(fn ($options, $group) => [
                            'label' => $group,
                            'options' => collect($options)
                                ->map(fn ($option) => [
                                    'label' => $option,
                                    'value' => $option,
                                ])
                                ->values()
                                ->all(),
                        ])
                        ->values()
                        ->all(),

                    default => [],
                },
                'position' => $field->position,
                'is_depended' => $field->dependency !== null,
                'depends_on_field' => $field->dependency?->dependsOnField?->name,
                'depends_on_values' => $field->dependency?->values ?? [],
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
