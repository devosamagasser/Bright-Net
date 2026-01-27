<?php

namespace App\Modules\PriceRules\Domain\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Modules\PriceRules\Domain\Models\PriceFactor;
use App\Modules\PriceRules\Domain\Models\ProductPriceFactor;
use App\Modules\PriceRules\Domain\Repositories\PriceRulesRepositoryInterface;
use App\Modules\PriceRules\Domain\ValueObjects\PriceFactorStatus;
use App\Modules\Products\Domain\Models\ProductPrice;

class FlattenPriceFactorsHistoryJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(
        public readonly array $factorsIds,
        public readonly PriceRulesRepositoryInterface $repository
    ) {
    }

    public function handle(): void
    {
        try {
            DB::transaction(function () {

                $priceIds = ProductPriceFactor::whereIn('factor_id', $this->factorsIds)
                    ->pluck('price_id')
                    ->unique()
                    ->toArray();

                $productPrices = ProductPrice::whereIn('id', $priceIds)->get();

                foreach ($productPrices as $productPrice) {
                    $this->flattenFactorsIntoBasePrice($productPrice);
                }

                PriceFactor::whereIn('id', $this->factorsIds)->forceDelete();
            });
        } catch (\Exception $e) {
            Log::error('Failed to flatten price factors history', [
                'factors_ids' => $this->factorsIds,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            throw $e;
        }
    }

    /**
     * Flatten factors into base price
     */
    private function flattenFactorsIntoBasePrice(ProductPrice $productPrice): void
    {
        $allActiveFactors = $this->repository->getActivePriceFactorsForPrice($productPrice->id);
        $remainingFactors = $allActiveFactors->filter(function ($factor)  {
            return in_array($factor->id, $this->factorsIds);
        });

        $remainingCumulativeFactor = 1.0;
        foreach ($remainingFactors as $factor) {
            $remainingCumulativeFactor *= (float) $factor->factor;
        }

        $newBasePrice = (float) $productPrice->price * $remainingCumulativeFactor;
        $productPrice->price = round($newBasePrice, 2);
        $productPrice->save();
    }

}

