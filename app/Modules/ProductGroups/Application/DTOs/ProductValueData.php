<?php

namespace App\Modules\Products\Application\DTOs;

use App\Modules\DataSheets\Application\DTOs\DataFieldData;
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
        $field = $productFieldValue->field;

        return new self(
            field: $field ? DataFieldData::fieldAttributes($field) : [],
            value: $field ? self::normalizeValue(
                $productFieldValue->value,
                $field->type
            ) : $productFieldValue->value,
        );
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

    public static function serializeValue(ProductFieldValue $productFieldValue, DataField $field): mixed
    {
        return $field ? self::normalizeValue(
            $productFieldValue->value,
            $field->type
        ) : $productFieldValue->value;
    }
}
