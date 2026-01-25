<?php

namespace App\Modules\PriceRules\Application\DTOs;

use App\Modules\PriceRules\Domain\Models\CurrencyTransformFactor;

class CurrencyTransformFactorData
{
    /**
     * @param array<string, mixed> $attributes
     */
    private function __construct(
        public readonly array $attributes,
    ) {
    }

    public static function fromModel(CurrencyTransformFactor $factor): self
    {
        return new self([
            'id' => (int) $factor->id,
            'supplier_id' => (int) $factor->supplier_id,
            'from' => $factor->from?->value,
            'to' => $factor->to?->value,
            'factor' => (float) $factor->factor,
            'created_at' => $factor->created_at?->toDateTimeString(),
            'updated_at' => $factor->updated_at?->toDateTimeString(),
        ]);
    }
}

