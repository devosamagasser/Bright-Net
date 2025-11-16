<?php

namespace App\Modules\DataSheets\Application\DTOs;

use Illuminate\Support\Arr;
use App\Modules\DataSheets\Application\DTOs\DependedFieldInput;

class DataFieldInput
{
    /**
     * @param  array<string, mixed>  $attributes
     * @param  array<string, array<string, mixed>>  $translations
     * @param  array<int, DependedFieldInput>  $dependencies
     */
    private function __construct(
        public readonly ?int $id,
        public readonly array $attributes,
        public readonly array $translations,
        public readonly array $dependencies,
    ) {
    }

    /**
     * @param  array<string, mixed>  $payload
     */
    public static function fromArray(array $payload): self
    {
        $translations = Arr::pull($payload, 'translations', []);
        $id = Arr::pull($payload, 'id');
        $dependenciesPayload = Arr::pull($payload, 'dependencies', []);
        $dependencies = array_map(
            static fn (array $dependency) => DependedFieldInput::fromArray($dependency),
            $dependenciesPayload
        );

        return new self(
            id: $id !== null ? (int) $id : null,
            attributes: $payload,
            translations: $translations,
            dependencies: $dependencies,
        );
    }
}
