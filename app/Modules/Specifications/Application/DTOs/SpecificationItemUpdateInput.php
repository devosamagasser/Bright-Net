<?php

namespace App\Modules\Specifications\Application\DTOs;

class SpecificationItemUpdateInput
{
    private function __construct(
        private readonly array $attributes,
    ) {
    }

    /**
     * @param  array<string, mixed>  $data
     */
    public static function fromArray(array $data): self
    {
        $normalized = [];

        foreach ($data as $key => $value) {
            $normalized[$key] = match ($key) {
                'quantity' => $value !== null ? (int) $value : null,
                'position' => $value !== null ? (int) $value : null,
                default => $value,
            };
        }

        return new self($normalized);
    }

    /**
     * @return array<string, mixed>
     */
    public function attributes(): array
    {
        return array_filter(
            $this->attributes,
            static fn ($value) => $value !== null,
        );
    }
}


