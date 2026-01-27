<?php

namespace App\Modules\PriceRules\Domain\Jobs;

use App\Modules\PriceRules\Domain\Models\ProductPriceFactor;
use App\Modules\Products\Domain\Models\ProductPrice;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ApplyProductPricesFactor implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, \Illuminate\Bus\Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct(
        public readonly int $supplierId,
        public readonly int $factorId,
        public readonly ?array $productIds,
        public readonly ?int $brandId,
        public readonly ?int $categoryId,
        public readonly ?int $subcategoryId,
        public readonly ?int $familyId)
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $productPrices = ProductPrice::join('products', 'products.id', '=', 'product_prices.product_id')
            ->where('products.supplier_id', $this->supplierId)
            ->when(
                value: $this->brandId,
                callback: function ($q) {
                    $q->where('products.supplier_brand_id', $this->brandId)
                        ->when($this->categoryId, fn ($q) => $q->where('products.supplier_department_id', $this->categoryId))
                        ->when($this->subcategoryId, fn ($q) => $q->where('products.subcategory_id', $this->subcategoryId))
                        ->when($this->familyId, fn ($q) => $q->where('products.family_id', $this->familyId));
                },
                default: function ($q)  {
                    $q->whereIn('product_prices.product_id', $this->productIds);
                })
            ->select('product_prices.*')
            ->get();

        $productPriceFactors = $productPrices->map(function (ProductPrice $price)  {
            return [
                'price_id' => $price->id,
                'factor_id' => $this->factorId,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        })->toArray();

        if (!empty($productPriceFactors)) {
            ProductPriceFactor::insert($productPriceFactors);
        }
    }
}
