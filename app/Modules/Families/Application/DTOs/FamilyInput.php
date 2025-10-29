<?php

namespace App\Modules\Families\Application\DTOs;

use Illuminate\Support\Arr;

class FamilyInput
{
    /**
     * @param  array<string, mixed>  $attributes
     * @param  array<string, array<string, mixed>>  $translations
     * @param  array<string, mixed>  $values
     */
    private function __construct(
        public readonly array $attributes,
        public readonly array $translations,
        public readonly array $values,
    ) {
    }

    /**
     * @param  array<string, mixed>  $payload
     */
    public static function fromArray(array $payload): self
    {
        $translations = Arr::pull($payload, 'translations', []);
        $values = Arr::pull($payload, 'values', []);

        return new self(
            attributes: $payload,
            translations: $translations,
            values: is_array($values) ? $values : [],
        );
    }
}
