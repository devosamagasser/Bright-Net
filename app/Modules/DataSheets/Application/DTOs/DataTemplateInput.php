<?php

namespace App\Modules\DataSheets\Application\DTOs;

use Illuminate\Support\Arr;

class DataTemplateInput
{
    /**
     * @param  array<string, mixed>  $attributes
     * @param  array<string, array<string, mixed>>  $translations
     * @param  array<int, DataFieldInput>  $fields
     */
    private function __construct(
        public readonly array $attributes,
        public readonly array $translations,
        public readonly array $fields,
    ) {
    }

    /**
     * Build the DTO from validated payload.
     *
     * @param  array<string, mixed>  $payload
     */
    public static function fromArray(array $payload): self
    {
        $translations = Arr::pull($payload, 'translations', []);
        $fieldsPayload = Arr::pull($payload, 'fields', []);

        $fields = [];
        foreach (array_values($fieldsPayload) as $index => $field) {
            $field['position'] = $field['position'] ?? $index + 1;
            $fields[] = DataFieldInput::fromArray($field);
        }

        return new self(
            attributes: $payload,
            translations: $translations,
            fields: $fields,
        );
    }
}
