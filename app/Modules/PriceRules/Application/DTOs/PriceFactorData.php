<?php

namespace App\Modules\PriceRules\Application\DTOs;

use App\Modules\PriceRules\Domain\Models\PriceFactor;

class PriceFactorData
{
    /**
     * @param array<string, mixed> $attributes
     */
    private function __construct(
        public readonly array $attributes,
    ) {
    }

    public static function fromModel(PriceFactor $factor): self
    {
        return new self([
            'id' => (int) $factor->id,
            'supplier_id' => (int) $factor->supplier_id,
            'user_id' => (int) $factor->user_id,
            'factor' => (float) $factor->factor,
            'status' => $factor->status?->value,
            'status_label' => $factor->status?->label(),
            'parent_factor_id' => $factor->parent_factor_id ? (int) $factor->parent_factor_id : null,
            'notes' => $factor->notes,
            'user' => $factor->relationLoaded('user') && $factor->user ? [
                'id' => (int) $factor->user->id,
                'name' => $factor->user->name ?? null,
            ] : null,
            'parent_factor' => $factor->relationLoaded('parentFactor') && $factor->parentFactor ? [
                'id' => (int) $factor->parentFactor->id,
                'factor' => (float) $factor->parentFactor->factor,
            ] : null,
            'created_at' => $factor->created_at?->toDateTimeString(),
            'updated_at' => $factor->updated_at?->toDateTimeString(),
        ]);
    }
}

