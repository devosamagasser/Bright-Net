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
    public function applyPriceFactor(int $supplier_id, float $factor, int $userId, ?string $notes = null,): PriceFactor
    {
        $priceFactor = PriceFactor::create([
            'supplier_id' => $supplier_id,
            'user_id' => $userId,
            'factor' => $factor,
            'status' => PriceFactorStatus::ACTIVE,
            'notes' => $notes,
        ]);
        return $priceFactor->load(['user', 'productPriceFactors']);
    }

    public function getPriceFactorHistory(Supplier $supplier, ?int $perPage = null): LengthAwarePaginator|Collection
    {
        $query = PriceFactor::where('supplier_id', $supplier->id)
            ->whereNull('deleted_at')
            ->with(['user', 'parentFactor'])
            ->orderBy('created_at', 'desc');

        if ($perPage !== null) {
            return $query->paginate($perPage);
        }

        return $query->get();
    }

    public function getProductsIdsByPriceFactor(int $factorId): array
    {
        $selectedFactor = PriceFactor::find($factorId);

        if ($selectedFactor === null) {
            return [];
        }

        $selectedCreatedAt = $selectedFactor->created_at;

        // Get all factors with created_at <= selected factor's created_at
        // Including the selected factor itself
        $factorIds = PriceFactor::where('supplier_id', $selectedFactor->supplier_id)
            ->whereNull('deleted_at')
            ->where('status', PriceFactorStatus::ACTIVE)
            ->where('created_at', '<=', $selectedCreatedAt)
            ->orderBy('created_at', 'asc')
            ->pluck('id')
            ->toArray();

        // Get all price IDs that have any of these factors applied
        $priceIds = ProductPriceFactor::whereIn('factor_id', $factorIds)
            ->pluck('price_id')
            ->unique();

        return ProductPrice::whereIn('id', $priceIds)
            ->pluck('product_id')
            ->unique()
            ->toArray();
    }

    public function getActivePriceFactorsForPrice(int $priceId): Collection
    {
        return PriceFactor::whereHas('productPriceFactors', function ($query) use ($priceId) {
            $query->where('price_id', $priceId);
        })
            ->where('status', PriceFactorStatus::ACTIVE)
            ->whereNull('deleted_at')
            ->orderBy('created_at', 'asc')
            ->get();
    }

    public function getActivePriceFactorsForPriceUpToFactor(int $priceId, \DateTime $maxFactorCreatedAt): Collection
    {
        return PriceFactor::whereHas('productPriceFactors', function ($query) use ($priceId) {
            $query->where('price_id', $priceId);
        })
            ->where('status', PriceFactorStatus::ACTIVE)
            ->whereNull('deleted_at')
            ->where('created_at', '<=', $maxFactorCreatedAt)
            ->orderBy('created_at', 'asc')
            ->get();
    }

    public function findPriceFactor(int $factorId): ?PriceFactor
    {
        return PriceFactor::with(['user', 'productPriceFactors.price.product', 'parentFactor'])
            ->find($factorId);
    }

    public function getFactorsToFlatten(int $supplier_id, int $factorId): array
    {
        $factorIds = PriceFactor::where('supplier_id', $supplier_id)
            ->whereNull('deleted_at')
            ->where('status', PriceFactorStatus::ACTIVE)
            ->where('id', '<=', $factorId)
            ->orderBy('created_at', 'asc')
            ->pluck('id')
            ->toArray();

        return $factorIds;
    }

    public function restorePriceFactor(int $factorId): PriceFactor
    {
        return DB::transaction(function () use ($factorId) {
            $selectedFactor = PriceFactor::withTrashed()->findOrFail($factorId);

            // Restore the selected factor if it was soft deleted
            if ($selectedFactor->trashed()) {
                $selectedFactor->restore();
            }

            // Activate the selected factor if it was reverted
            if ($selectedFactor->isReverted()) {
                $selectedFactor->activate();
            }

            // Get all factors created after the selected factor
            $factorsToDelete = PriceFactor::where('supplier_id', $selectedFactor->supplier_id)
                ->whereNull('deleted_at')
                ->where('created_at', '>', $selectedFactor->created_at)
                ->get();

            // Soft delete all factors after the selected one
            foreach ($factorsToDelete as $factor) {
                $factor->delete();
            }

            return $selectedFactor->fresh(['user', 'productPriceFactors.price.product', 'parentFactor']);
        });
    }
}
