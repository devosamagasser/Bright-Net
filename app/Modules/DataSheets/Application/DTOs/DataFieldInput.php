<?php

namespace App\Modules\DataSheets\Application\DTOs;

use Illuminate\Support\Arr;

class DataFieldInput
{
    /**
     * @param  array<string, mixed>  $attributes
     * @param  array<string, array<string, mixed>>  $translations
     */
    private function __construct(
        public readonly ?int $id,
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
        $id = Arr::pull($payload, 'id');

        return new self(
            id: $id !== null ? (int) $id : null,
            attributes: $payload,
            translations: $translations,
        );
    }
}
