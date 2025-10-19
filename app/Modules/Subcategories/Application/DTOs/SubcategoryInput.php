<?php

namespace App\Modules\Subcategories\Application\DTOs;

use Illuminate\Support\Arr;

class SubcategoryInput
{
    /**
     * @param  array<string, mixed>  $attributes
     * @param  array<string, array<string, mixed>>  $translations
     */
    private function __construct(
        public readonly array $attributes,
        public readonly array $translations,
    ) {
    }

    /**
     * @param  array<string, mixed>  $payload
     */
    public static function fromArray(array $payload): self
    {
        $translations = Arr::pull($payload, 'translations', []);

        return new self(
            attributes: $payload,
            translations: $translations,
        );
    }
}
