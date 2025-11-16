<?php

namespace App\Modules\DataSheets\Application\DTOs;

class DependedFieldInput
{
    /**
     * @param  array<int, string>  $values
     */
    private function __construct(
        public readonly ?int $id,
        public readonly ?int $dependsOnFieldId,
        public readonly array $values,
    ) {
    }

    /**
     * @param  array<string, mixed>  $payload
     */
    public static function fromArray(array $payload): self
    {
        return new self(
            id: isset($payload['id']) ? (int) $payload['id'] : null,
            dependsOnFieldId: isset($payload['depends_on_field_id'])
                ? (int) $payload['depends_on_field_id']
                : null,
            values: array_values($payload['values'] ?? []),
        );
    }
}
