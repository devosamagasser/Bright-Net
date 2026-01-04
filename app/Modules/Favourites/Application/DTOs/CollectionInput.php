<?php

namespace App\Modules\Favourites\Application\DTOs;

class CollectionInput
{
    /**
     * @param array<string, mixed> $attributes
     */
    private function __construct(
        public readonly array $attributes,
    ) {
    }

    /**
     * Build the DTO from validated data.
     *
     * @param array<string, mixed> $payload
     */
    public static function fromArray(array $payload): self
    {
        return new self(
            attributes: $payload,
        );
    }
}

