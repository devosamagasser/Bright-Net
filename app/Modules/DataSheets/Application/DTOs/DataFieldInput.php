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
        public readonly ?FieldDependencyInput $dependency,
    ) {
    }

    /**
     * @param  array<string, mixed>  $payload
     */
    public static function fromArray(array $payload): self
    {
        $translations = Arr::pull($payload, 'translations', []);
        $id = Arr::pull($payload, 'id');

        $isDepended = filter_var(Arr::pull($payload, 'is_depended', false), FILTER_VALIDATE_BOOLEAN);
        $dependsOnField = Arr::pull($payload, 'depends_on_field');

        $dependsOnValues = Arr::wrap(Arr::pull($payload, 'depends_on_values', []));

        $dependency = null;

        if ($isDepended && is_string($dependsOnField)) {
            $cleanValues = array_values(array_filter($dependsOnValues, fn ($value) => $value !== null && $value !== ''));

            if ($cleanValues !== []) {
                $dependency = FieldDependencyInput::make($dependsOnField, $cleanValues);
            }
        }
        
        return new self(
            id: $id !== null ? (int) $id : null,
            attributes: $payload,
            translations: $translations,
            dependency: $dependency,
        );
    }
}
