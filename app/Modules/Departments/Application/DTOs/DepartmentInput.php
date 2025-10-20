<?php

namespace App\Modules\Departments\Application\DTOs;

use Illuminate\Support\Arr;
use Illuminate\Http\UploadedFile;

class DepartmentInput
{
    /**
     * @param  array<string, mixed>  $attributes
     * @param  array<string, array<string, mixed>>  $translations
     */
    private function __construct(
        public readonly array $attributes,
        public readonly array $translations,
        public readonly UploadedFile $cover,
    ) {
    }

    /**
     * Build the DTO from validated data.
     *
     * @param  array<string, mixed>  $payload
     */
    public static function fromArray(array $payload): self
    {
        $translations = Arr::pull($payload, 'translations', []);
        $cover = Arr::pull($payload, 'cover', []);

        return new self(
            attributes: $payload,
            translations: $translations,
            cover: $cover,
        );
    }
}
