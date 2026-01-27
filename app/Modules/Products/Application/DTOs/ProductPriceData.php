<?php

namespace App\Modules\Products\Application\DTOs;

use App\Models\Supplier;
use App\Modules\PriceRules\Domain\Services\PriceCalculationService;
use App\Modules\Products\Domain\Models\ProductPrice;

class ProductPriceData
{
    /**
     * @param  array<string, mixed>  $attributes
     */
    private function __construct(
        public readonly array $attributes,
    ) {
    }

    public static function fromModel(
        ProductPrice $price,
        ?string $targetCurrency = null,
        ?Supplier $supplier = null,
        ?\DateTime $maxFactorCreatedAt = null
    ): self {
        $attributes = [
            'id' => (int) $price->getKey(),
            'price' => (float) $price->price,
            'from' => $price->from,
            'to' => $price->to,
            'currency' => $price->currency?->value,
            'delivery_time_unit' => $price->delivery_time_unit?->value,
            'delivery_time_value' => $price->delivery_time_value,
            'vat_status' => $price->vat_status,
        ];

        // Calculate final price if target currency and supplier are provided
        if ($targetCurrency !== null && $supplier !== null) {
            $calculationService = app(PriceCalculationService::class);

            // Use calculateFinalPriceUpToFactor if maxFactorCreatedAt is provided
            if ($maxFactorCreatedAt !== null) {
                $calculatedPrice = $calculationService->calculateFinalPriceUpToFactor(
                    $price,
                    $targetCurrency,
                    $supplier,
                    $maxFactorCreatedAt
                );
            } else {
                $calculatedPrice = $calculationService->calculateFinalPrice(
                    $price,
                    $targetCurrency,
                    $supplier
                );
            }

            $attributes['price'] = $calculatedPrice['final_price'];
            $attributes['currency'] =  $calculatedPrice['currency'];
        }

        return new self($attributes);
    }
}
