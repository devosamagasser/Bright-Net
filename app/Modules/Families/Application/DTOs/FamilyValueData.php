<?php

namespace App\Modules\Families\Application\DTOs;

use App\Modules\Families\Domain\Models\FamilyFieldValue;
use App\Modules\DataSheets\Domain\Models\DataField;
use App\Modules\DataSheets\Domain\ValueObjects\DataFieldType;

class FamilyValueData
{
    /**
     * @param  array<string, mixed>  $field
     */
    private function __construct(
        public readonly array $field,
        public readonly mixed $value,
    ) {
    }

    public static function fromModel(FamilyFieldValue $familyFieldValue): self
    {
        /** @var DataField|null $field */
        $field = $familyFieldValue->field;

        if ($field) {
            $field->loadMissing('dependencies.dependsOnField');
        }

        return new self(
            field: $field ? self::fieldSummary($field) : [],
            value: $field ? self::normalizeValue($familyFieldValue->value, $field->type) : $familyFieldValue->value,
        );
    }

    /**
     * @return array<string, mixed>
     */
    private static function fieldSummary(DataField $field): array
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

        return [
            'id' => $field->getKey(),
            'slug' => $field->slug ?? null,
            'position' => $field->position,
            'label' => $field->label,
            'type' => $field->type->value,
            'name' => $field->name,
            'placeholder' => $field->placeholder,
            'is_required' => $field->is_required,
            'is_filterable' => $field->is_filterable,
            'options' => $field->options ?? [],
            'is_dependent' => $dependencies->isNotEmpty(),
            'depends_on_field_name' => $primaryDependency?->dependsOnField?->name,
            'depends_on_values' => $primaryDependency ? array_values($primaryDependency->values ?? []) : [],
            'dependencies' => $dependencies->all(),
        ];
    }

    private static function normalizeValue(mixed $value, DataFieldType $type): mixed
    {
        return match ($type) {
            DataFieldType::MULTISELECT => is_array($value) ? array_values($value) : (array) $value,
            DataFieldType::BOOLEAN => filter_var($value, FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE),
            DataFieldType::NUMBER => is_numeric($value) ? $value + 0 : $value,
            default => $value,
        };
    }
}
