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

        $normalizedTranslations = [];
        $name = $payload['name'] ?? null;

        foreach ($translations as $locale => $fields) {
            if ($name === null && isset($fields['name'])) {
                $name = $fields['name'];
            }

            $filtered = array_filter(
                [
                    'description' => $fields['description'] ?? null,
                ],
                static fn ($value) => $value !== null,
            );

            if ($filtered !== []) {
                $normalizedTranslations[$locale] = $filtered;
            }
        }

        if ($name !== null) {
            $payload['name'] = $name;
        }

        return new self(
            attributes: $payload,
            translations: $normalizedTranslations,
            values: is_array($values) ? $values : [],
        );
    }
}
