<?php
namespace App\Modules\Products\Domain\Services;

use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use App\Modules\Products\Domain\Models\Product;

class ProductPriceService
{
        /**
     * @param  array<string, mixed>  $relations
     */
    public function syncPrices(Product $product, array $prices): void
    {
        $product->prices()->delete();

        if ($prices === []) {
            return;
        }

        $rows = collect($prices)->map(function (array $price) use ($product) {
            return [
                'product_id' => $product->getKey(),
                'price' => Arr::get($price, 'price'),
                'from' => Arr::get($price, 'from'),
                'to' => Arr::get($price, 'to'),
                'currency' => Arr::get($price, 'currency'),
                'delivery_time_unit' => Arr::get($price, 'delivery_time_unit'),
                'delivery_time_value' => Arr::get($price, 'delivery_time_value'),
                'vat_status' => Arr::get($price, 'vat_status'),
                'created_at' => now(),
                'updated_at' => now(),
            ];
        })->all();

        DB::table('product_prices')->insert($rows);
    }

    public function calculateBudgetPrice(Product $product, int $quantity): float
    {
        $applicablePrice = $product->prices()
            ->where('from', '<=', $quantity)
            ->where(function ($query) use ($quantity) {
                $query->where('to', '>=', $quantity)
                    ->orWhereNull('to');
            })
            ->orderBy('from', 'desc')
            ->first();

        if ($applicablePrice === null) {
            throw new \InvalidArgumentException('No applicable price found for the given quantity.');
        }

        return $applicablePrice->price * $quantity;
    }

}
