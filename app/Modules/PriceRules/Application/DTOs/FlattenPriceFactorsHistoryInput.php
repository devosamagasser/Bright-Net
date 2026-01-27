<?php

namespace App\Modules\PriceRules\Application\DTOs;

class FlattenPriceFactorsHistoryInput
{
    private function __construct(
        public readonly int $factorId,
    ) {
    }

    /**
     * @param array<string, mixed> $data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            factorId: (int) $data['factor_id'],
        );
    }
}

