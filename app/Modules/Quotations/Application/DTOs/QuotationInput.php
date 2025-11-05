<?php

namespace App\Modules\Quotations\Application\DTOs;

class QuotationInput
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
                'meta' => is_array($value) ? $value : null,
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
