<?php

namespace App\Modules\DataSheets\Application\DTOs;

class FieldDependencyInput
{
    /**
     * @param  array<int, string>  $values
     */
    private function __construct(
        public readonly string $field,
        public readonly array $values,
    ) {
    }

    /**
     * @param  array<int|string, mixed>  $values
     */
    public static function make(string $field, array $values): self
    {
        return new self(
            field: $field,
            values: array_values(array_unique($values)),
        );
    }
}
