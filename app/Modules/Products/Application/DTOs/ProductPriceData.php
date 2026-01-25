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

    public static function fromModel(ProductPrice $price, ?string $targetCurrency = null, ?Supplier $supplier = null): self
    {
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
            $calculatedPrice = $calculationService->calculateFinalPrice(
                $price,
                $targetCurrency,
                $supplier
            );

            $attributes['calculated_price'] = [
                'original_price' => $calculatedPrice['original_price'],
                'original_currency' => $calculatedPrice['original_currency'],
                'converted_price' => $calculatedPrice['converted_price'],
                'final_price' => $calculatedPrice['final_price'],
                'currency' => $calculatedPrice['currency'],
                'factors_applied' => $calculatedPrice['factors_applied'],
            ];
        }

        return new self($attributes);
    }
}
