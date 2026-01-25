<?php

namespace App\Modules\PriceRules\Application\DTOs;

class PriceFactorInput
{
    /**
     * @param array<int> $productIds
     */
    private function __construct(
        public readonly array $productIds,
        public readonly float $factor,
        public readonly ?string $notes,
    ) {
    }

    /**
     * @param array<string, mixed> $data
     */
    public static function fromArray(array $data): self
    {
        $productIds = $data['product_ids'] ?? [];
        if (!is_array($productIds)) {
            $productIds = [];
        }

        return new self(
            productIds: array_map(fn ($id) => (int) $id, $productIds),
            factor: (float) $data['factor'],
            notes: $data['notes'] ?? null,
        );
    }
}

