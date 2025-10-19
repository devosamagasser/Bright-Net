<?php

namespace App\Modules\SolutionsCatalog\Application\DTOs;

use Illuminate\Support\Arr;

class SolutionInput
{
    /**
     * @param  array<string, string>  $name
     */
    public function __construct(
        public readonly array $attributes,
        public readonly array $translations
    ) {
    }

    /**
     * Build the DTO from validated request data.
     *
     * @param  array<string, mixed>  $payload
     */
    public static function fromArray(array $payload): self
    {
        $translations = Arr::pull($payload, 'translations');

        return new self(
            attributes: $payload,
            translations: $translations,
        );
    }
}
