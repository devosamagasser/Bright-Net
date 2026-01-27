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

    public function applyPriceFactor(int $supplier_id, float $factor, int $userId, ?string $notes = null): PriceFactor;

    public function getPriceFactorHistory(Supplier $supplier, ?int $perPage = null): LengthAwarePaginator|Collection;

    public function getProductsIdsByPriceFactor(int $factorId): array;

    public function getActivePriceFactorsForPrice(int $priceId): Collection;

    /**
     * Get active price factors for a price up to a specific factor's created_at
     *
     * @param int $priceId
     * @param \DateTime $maxFactorCreatedAt
     * @return Collection
     */
    public function getActivePriceFactorsForPriceUpToFactor(int $priceId, \DateTime $maxFactorCreatedAt): Collection;

    public function findPriceFactor(int $factorId): ?PriceFactor;

    /**
     * Get factor IDs to flatten (selected factor + all factors before it chronologically)
     *
     * @param int $factorId
     * @return array{factor_ids: array<int>, selected_factor_created_at: \DateTime|null}
     */
    public function getFactorsToFlatten(int $supplier_id, int $factorId): array;

    /**
     * Restore a price factor and soft delete all factors created after it
     *
     * @param int $factorId
     * @return PriceFactor
     */
    public function restorePriceFactor(int $factorId): PriceFactor;
}
