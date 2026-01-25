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
        return new self($data);
    }

    /**
     * @return array<string, mixed>
     */
    public function attributes(): array
    {
        return $this->attributes;
    }
}
