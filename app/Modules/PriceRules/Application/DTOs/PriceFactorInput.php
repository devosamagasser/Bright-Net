<?php

namespace App\Modules\PriceRules\Application\DTOs;

class PriceFactorInput
{
    /**
     * @param array<int> $productIds
     */
    private function __construct(
        public readonly ?array $productIds,
        public readonly float $factor,
        public readonly ?string $notes,
        public readonly ?int $brandId,
        public readonly ?int $categoryId,
        public readonly ?int $subcategoryId,
        public readonly ?int $familyId,
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
            brandId: $data['brand_id'] ?? null,
            categoryId: $data['category_id'] ?? null,
            subcategoryId: $data['subcategory_id'] ?? null,
            familyId: $data['family_id'] ?? null,
        );
    }
}

