<?php

namespace App\Modules\Quotations\Application\DTOs;

class QuotationProductUpdateInput
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
                'quantity', 'position' => $value !== null ? (int) $value : null,
                'price', 'discount', 'list_price' => $value !== null ? (float) $value : null,
                'vat_included' => $value !== null ? (bool) $value : null,
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
