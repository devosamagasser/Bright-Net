<?php

namespace App\Modules\PriceRules\Domain\Services;

use App\Models\Supplier;
use App\Modules\PriceRules\Domain\Models\CurrencyTransformFactor;
use App\Modules\PriceRules\Domain\Repositories\PriceRulesRepositoryInterface;
use App\Modules\Products\Domain\Models\ProductPrice;
use App\Modules\PriceRules\Domain\ValueObjects\PriceCurrency;

class PriceCalculationService
{
    public function __construct(
        private readonly PriceRulesRepositoryInterface $priceRulesRepository
    ) {
    }

    /**
     * Calculate final price with currency conversion and price factors applied
     *
     * @param ProductPrice $price The original product price
     * @param string $targetCurrency The target currency code
     * @param Supplier $supplier The supplier to get conversion factors from
     * @return array{original_price: float, converted_price: float, final_price: float, currency: string, factors_applied: array}
     */
    public function calculateFinalPrice(
        ProductPrice $price,
        string $targetCurrency,
        Supplier $supplier
    ): array {
        $originalPrice = (float) $price->price;
        $originalCurrency = $price->currency?->value ?? PriceCurrency::USD->value;

        // Step 1: Convert currency if needed
        $convertedPrice = $this->convertCurrency($originalPrice, $originalCurrency, $targetCurrency, $supplier);

        // Step 2: Apply all active price factors (cumulative)
        $activeFactors = $this->priceRulesRepository->getActivePriceFactorsForPrice($price->id);
        $finalPrice = $convertedPrice;
        $factorsApplied = [];

        foreach ($activeFactors as $factor) {
            $factorValue = (float) $factor->factor;
            $finalPrice = $finalPrice * $factorValue;
            $factorsApplied[] = [
                'id' => $factor->id,
                'factor' => $factorValue,
                'applied_at' => $factor->created_at?->toDateTimeString(),
            ];
        }

        return [
            'original_price' => $originalPrice,
            'original_currency' => $originalCurrency,
            'converted_price' => $convertedPrice,
            'final_price' => round($finalPrice, 2),
            'currency' => $targetCurrency,
            'factors_applied' => $factorsApplied,
        ];
    }

    /**
     * Convert price from one currency to another
     */
    private function convertCurrency(
        float $amount,
        string $fromCurrency,
        string $toCurrency,
        Supplier $supplier
    ): float {
        // If same currency, no conversion needed
        if ($fromCurrency === $toCurrency) {
            return $amount;
        }

        // Try to get direct conversion factor
        $conversionFactor = $this->priceRulesRepository->getCurrencyTransformFactor(
            $supplier,
            $fromCurrency,
            $toCurrency
        );

        if ($conversionFactor !== null && $conversionFactor->isValid()) {
            return $conversionFactor->convert($amount);
        }

        // If direct conversion not found, try reverse (to -> from, then invert)
        $reverseFactor = $this->priceRulesRepository->getCurrencyTransformFactor(
            $supplier,
            $toCurrency,
            $fromCurrency
        );

        if ($reverseFactor !== null && $reverseFactor->isValid()) {
            // Invert: if 1 USD = 50 EGP, then 1 EGP = 1/50 USD
            $invertedFactor = 1 / (float) $reverseFactor->factor;
            return $amount * $invertedFactor;
        }

        // If no conversion factor found, return original amount
        return $amount;
    }

    /**
     * Get conversion factor between two currencies
     */
    public function getConversionFactor(
        Supplier $supplier,
        string $fromCurrency,
        string $toCurrency
    ): ?float {
        if ($fromCurrency === $toCurrency) {
            return 1.0;
        }

        $factor = $this->priceRulesRepository->getCurrencyTransformFactor(
            $supplier,
            $fromCurrency,
            $toCurrency
        );

        if ($factor !== null && $factor->isValid()) {
            return (float) $factor->factor;
        }

        // Try reverse
        $reverseFactor = $this->priceRulesRepository->getCurrencyTransformFactor(
            $supplier,
            $toCurrency,
            $fromCurrency
        );

        if ($reverseFactor !== null && $reverseFactor->isValid()) {
            return 1 / (float) $reverseFactor->factor;
        }

        return null;
    }
}

