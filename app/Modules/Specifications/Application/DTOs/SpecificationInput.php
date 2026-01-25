<?php

namespace App\Modules\Specifications\Application\DTOs;

class SpecificationInput
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
                'company_id' => $value !== null ? (int) $value : null,
                'general_notes' => is_array($value) ? $value : null,
                'show_quantity',
                'show_approval',
                'show_reference' => $value !== null ? (bool) $value : null,
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
        return $this->attributes;
    }
}


