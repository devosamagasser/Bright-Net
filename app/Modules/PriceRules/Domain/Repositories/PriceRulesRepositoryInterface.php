<?php

namespace App\Modules\PriceRules\Domain\Repositories;

use App\Models\Supplier;
use App\Modules\PriceRules\Domain\Models\CurrencyTransformFactor;
use App\Modules\PriceRules\Domain\Models\PriceFactor;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

interface PriceRulesRepositoryInterface
{
    // Currency Transform Factors
    public function getCurrencyTransformFactors(Supplier $supplier): Collection;
    
    public function storeCurrencyTransformFactor(
        Supplier $supplier,
        string $from,
        string $to,
        float $factor
    ): CurrencyTransformFactor;
    
    public function updateCurrencyTransformFactor(
        CurrencyTransformFactor $currencyTransformFactor,
        float $factor
    ): CurrencyTransformFactor;
    
    public function deleteCurrencyTransformFactor(CurrencyTransformFactor $currencyTransformFactor): void;
    
    public function getCurrencyTransformFactor(Supplier $supplier, string $from, string $to): ?CurrencyTransformFactor;

    // Price Factors
    public function applyPriceFactor(
        Supplier $supplier,
        array $productIds,
        float $factor,
        int $userId,
        ?string $notes = null
    ): PriceFactor;
    
    public function revertPriceFactor(int $factorId): PriceFactor;
    
    public function reapplyPriceFactor(int $factorId): PriceFactor;
    
    public function getPriceFactorHistory(Supplier $supplier, ?int $perPage = null): LengthAwarePaginator|Collection;
    
    public function getProductsByPriceFactor(int $factorId): Collection;
    
    public function getActivePriceFactorsForPrice(int $priceId): Collection;
    
    public function findPriceFactor(int $factorId): ?PriceFactor;
}
