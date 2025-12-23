<?php

namespace App\Modules\Products\Application\DTOs;

use App\Modules\Products\Domain\Models\ProductFieldValue;
use App\Modules\DataSheets\Domain\Models\DataField;
use App\Modules\DataSheets\Domain\ValueObjects\DataFieldType;

class ProductValueData
{
    /**
     * @param  array<string, mixed>  $field
     */
    private function __construct(
        public readonly array $field,
        public readonly mixed $value,
    ) {
    }

    public static function fromModel(ProductFieldValue $productFieldValue): self
    {
        /** @var DataField|null $field */
        $field = $productFieldValue->field;

        return new self(
            field: $field ? self::fieldSummary($field) : [],
            value: $field ? self::normalizeValue($productFieldValue->value, $field->type) : $productFieldValue->value,
        );
    }

    /**
     * @return array<string, mixed>
     */
    private static function fieldSummary(DataField $field): array
    {
        return [
            'id' => $field->getKey(),
            'position' => $field->position,
            'label' => $field->label,
            'type' => $field->type->value,
            'name' => $field->name,
            'placeholder' => $field->placeholder,
            'is_required' => $field->is_required,
            'is_filterable' => $field->is_filterable,
            'options' => match ($field->type) {
                    DataFieldType::SELECT => collect($field->options ?? [])
                        ->map(fn ($option) => [
                            'label' => $option['label'] ?? $option,
                            'value' => $option['value'] ?? $option,
                        ])
                        ->values()
                        ->all(),
                    DataFieldType::MULTISELECT => collect($field->options ?? [])
                        ->map(fn ($option) => [
                            'label' => $option,
                            'value' => $option,
                        ])
                        ->values()
                        ->all(),
                    DataFieldType::GROUPEDSELECT => collect($field->options ?? [])
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
