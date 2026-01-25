<?php

namespace App\Modules\PriceRules\Infrastructure\Persistence\Eloquent;

use App\Models\Supplier;
use App\Modules\PriceRules\Domain\Models\CurrencyTransformFactor;
use App\Modules\PriceRules\Domain\Models\PriceFactor;
use App\Modules\PriceRules\Domain\Models\ProductPriceFactor;
use App\Modules\PriceRules\Domain\Repositories\PriceRulesRepositoryInterface;
use App\Modules\PriceRules\Domain\ValueObjects\PriceFactorStatus;
use App\Modules\Products\Domain\Models\Product;
use App\Modules\Products\Domain\Models\ProductPrice;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class EloquentPriceRulesRepository implements PriceRulesRepositoryInterface
{
    // Currency Transform Factors
    public function getCurrencyTransformFactors(Supplier $supplier): Collection
    {
        return CurrencyTransformFactor::where('supplier_id', $supplier->id)
            ->orderBy('from')
            ->orderBy('to')
            ->get();
    }

    public function storeCurrencyTransformFactor(
        Supplier $supplier,
        string $from,
        string $to,
        float $factor
    ): CurrencyTransformFactor {
        return CurrencyTransformFactor::create([
            'supplier_id' => $supplier->id,
            'from' => $from,
            'to' => $to,
            'factor' => $factor,
        ]);
    }

    public function updateCurrencyTransformFactor(
        CurrencyTransformFactor $currencyTransformFactor,
        float $factor
    ): CurrencyTransformFactor {
        $currencyTransformFactor->update(['factor' => $factor]);
        return $currencyTransformFactor->fresh();
    }

    public function deleteCurrencyTransformFactor(CurrencyTransformFactor $currencyTransformFactor): void
    {
        $currencyTransformFactor->delete();
    }

    public function getCurrencyTransformFactor(Supplier $supplier, string $from, string $to): ?CurrencyTransformFactor
    {
        return CurrencyTransformFactor::where('supplier_id', $supplier->id)
            ->where('from', $from)
            ->where('to', $to)
            ->first();
    }

    // Price Factors
    public function applyPriceFactor(
        Supplier $supplier,
        array $productIds,
        float $factor,
        int $userId,
        ?string $notes = null
    ): PriceFactor {
        return DB::transaction(function () use ($supplier, $productIds, $factor, $userId, $notes) {
            // Create the price factor
            $priceFactor = PriceFactor::create([
                'supplier_id' => $supplier->id,
                'user_id' => $userId,
                'factor' => $factor,
                'status' => PriceFactorStatus::ACTIVE,
                'notes' => $notes,
            ]);

            // Get all product prices for the specified products
            $productPrices = ProductPrice::whereIn('product_id', $productIds)->get();

            // Create ProductPriceFactor records for each price
            $productPriceFactors = $productPrices->map(function (ProductPrice $price) use ($priceFactor) {
                return [
                    'price_id' => $price->id,
                    'factor_id' => $priceFactor->id,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            })->toArray();

            if (!empty($productPriceFactors)) {
                ProductPriceFactor::insert($productPriceFactors);
            }

            return $priceFactor->load(['user', 'productPriceFactors.price.product']);
        });
    }

    public function revertPriceFactor(int $factorId): PriceFactor
    {
        $factor = PriceFactor::findOrFail($factorId);
        $factor->revert();
        return $factor->fresh();
    }

    public function reapplyPriceFactor(int $factorId): PriceFactor
    {
        return DB::transaction(function () use ($factorId) {
            $originalFactor = PriceFactor::findOrFail($factorId);

            // Create a new factor with the same values
            $newFactor = PriceFactor::create([
                'supplier_id' => $originalFactor->supplier_id,
                'user_id' => $originalFactor->user_id,
                'factor' => $originalFactor->factor,
                'status' => PriceFactorStatus::ACTIVE,
                'parent_factor_id' => $originalFactor->id,
                'notes' => $originalFactor->notes,
            ]);

            // Get all product prices that were affected by the original factor
            $originalProductPriceFactors = ProductPriceFactor::where('factor_id', $originalFactor->id)->get();

            // Create new ProductPriceFactor records
            $newProductPriceFactors = $originalProductPriceFactors->map(function (ProductPriceFactor $ppf) use ($newFactor) {
                return [
                    'price_id' => $ppf->price_id,
                    'factor_id' => $newFactor->id,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            })->toArray();

            if (!empty($newProductPriceFactors)) {
                ProductPriceFactor::insert($newProductPriceFactors);
            }

            return $newFactor->load(['user', 'productPriceFactors.price.product']);
        });
    }

    public function getPriceFactorHistory(Supplier $supplier, ?int $perPage = null): LengthAwarePaginator|Collection
    {
        $query = PriceFactor::where('supplier_id', $supplier->id)
            ->with(['user', 'parentFactor'])
            ->orderBy('created_at', 'desc');

        if ($perPage !== null) {
            return $query->paginate($perPage);
        }

        return $query->get();
    }

    public function getProductsByPriceFactor(int $factorId): Collection
    {
        $priceIds = ProductPriceFactor::where('factor_id', $factorId)
            ->pluck('price_id')
            ->unique();

        $productIds = ProductPrice::whereIn('id', $priceIds)
            ->pluck('product_id')
            ->unique();

        return Product::whereIn('id', $productIds)
            ->with(['prices', 'family'])
            ->get();
    }

    public function getActivePriceFactorsForPrice(int $priceId): Collection
    {
        return PriceFactor::whereHas('productPriceFactors', function ($query) use ($priceId) {
            $query->where('price_id', $priceId);
        })
            ->where('status', PriceFactorStatus::ACTIVE)
            ->orderBy('created_at', 'asc')
            ->get();
    }

    public function findPriceFactor(int $factorId): ?PriceFactor
    {
        return PriceFactor::with(['user', 'productPriceFactors.price.product', 'parentFactor'])
            ->find($factorId);
    }
}
