<?php

namespace App\Modules\Families\Application\DTOs;

use Illuminate\Support\Arr;
use Illuminate\Http\UploadedFile;

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
        public readonly ?UploadedFile $image = null,
    ) {
    }

    /**
     * @param  array<string, mixed>  $payload
     */
    public static function fromArray(array $payload): self
    {
        $translations = Arr::pull($payload, 'translations', []);
        $values = Arr::pull($payload, 'values', []);
        $image = Arr::pull($payload, 'image');

        return new self(
            attributes: $payload,
            translations: $translations,
            values: is_array($values) ? $values : [],
            image: $image instanceof UploadedFile ? $image : null
        );
    }
}
