<?php

namespace App\Modules\PriceRules\Application\DTOs;

use App\Modules\PriceRules\Domain\Models\PriceFactor;
use Illuminate\Support\Collection;

class PriceFactorHistoryData
{
    /**
     * @param array<string, mixed> $attributes
     * @param array<int, array<string, mixed>> $products
     */
    private function __construct(
        public readonly array $attributes,
        public readonly array $products,
    ) {
    }

    public static function fromModel(PriceFactor $factor, ?Collection $products = null): self
    {
        $productsData = [];
        if ($products !== null) {
            $productsData = $products->map(function ($product) {
                return [
                    'id' => (int) $product->id,
                    'code' => $product->code,
                    'name' => $product->name,
                ];
            })->toArray();
        }

        return new self(
            attributes: [
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
            ],
            products: $productsData,
        );
    }
}

