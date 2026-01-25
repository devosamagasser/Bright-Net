<?php

namespace App\Modules\PriceRules\Application\DTOs;

class CurrencyTransformFactorInput
{
    private function __construct(
        public readonly string $from,
        public readonly string $to,
        public readonly float $factor,
    ) {
    }

    /**
     * @param array<string, mixed> $data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            from: (string) $data['from'],
            to: (string) $data['to'],
            factor: (float) $data['factor'],
        );
    }
}

