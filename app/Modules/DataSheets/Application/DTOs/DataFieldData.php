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
        $dependencies = $field->dependencies->map(function ($dependency) {
            return [
                'id' => $dependency->getKey(),
                'depends_on_field_id' => $dependency->depends_on_field_id,
                'depends_on_field_name' => $dependency->dependsOnField?->name,
                'values' => $dependency->values ?? [],
            ];
        })->values();

        $primaryDependency = $field->dependencies->first();

        return new self(
            attributes: [
                'id' => $field->getKey(),
                'label' => $field->label,
                'placeholder' => $field->placeholder,
                'name' => $field->name,
                'type' => $field->type instanceof DataFieldType ? $field->type->value : $field->type,
                'is_required' => $field->is_required,
                'is_filterable' => $field->is_filterable,
                'options' => $field->options,
                'position' => $field->position,
                'is_dependent' => $dependencies->isNotEmpty(),
                'depends_on_field_name' => $primaryDependency?->dependsOnField?->name,
                'depends_on_values' => $primaryDependency ? array_values($primaryDependency->values ?? []) : [],
                'dependencies' => $dependencies->all(),
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
