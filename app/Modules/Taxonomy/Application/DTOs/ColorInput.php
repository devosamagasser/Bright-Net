<?php

namespace App\Modules\Taxonomy\Application\DTOs;

use Illuminate\Support\Arr;

class ColorInput
{
    /**
     * @param  array<string, mixed>  $attributes
     * @param  array<string, array<string, mixed>>  $translations
     */
    public function __construct(
        public readonly array $attributes,
        public readonly array $translations,
    ) {
    }

    /**
     * Build the DTO from validated request data.
     *
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
