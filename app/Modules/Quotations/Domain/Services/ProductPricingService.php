<?php

namespace App\Modules\Quotations\Domain\Services;

use Illuminate\Support\Collection;
use App\Modules\Products\Domain\Models\ProductPrice;
use App\Modules\Products\Domain\ValueObjects\PriceCurrency;

class ProductPricingService
{
    /**
     * Resolve the proper ProductPrice for a given quantity and build pricing snapshot.
     *
     * @param  Collection<int, ProductPrice>  $prices
     * @return array{
     *     list_price: float|null,
     *     currency: string,
     *     delivery_time_unit: string|null,
     *     delivery_time_value: mixed,
     *     vat_included: bool,
     *     snapshot: ?array
     * }
     */
    public function resolvePrice(Collection $prices, int $quantity): array
    {
        $price = $prices
            ->first(static fn (ProductPrice $price) => $quantity >= $price->from && $quantity <= $price->to);

        if ($price === null) {
            $price = $prices->sortBy('from')->first();
        }

        if ($price === null) {
            return [
                'list_price' => null,
                'currency' => PriceCurrency::EGP->value,
                'delivery_time_unit' => null,
                'delivery_time_value' => null,
                'vat_included' => false,
                'snapshot' => null,
            ];
        }

        return [
            'list_price' => (float) $price->price,
            'currency' => $price->currency?->value ?? PriceCurrency::EGP->value,
            'delivery_time_unit' => $price->delivery_time_unit?->value,
            'delivery_time_value' => $price->delivery_time_value,
            'vat_included' => (bool) $price->vat_status,
            'snapshot' => [
                'id' => (int) $price->getKey(),
                'from' => $price->from,
                'to' => $price->to,
                'price' => (float) $price->price,
                'currency' => $price->currency?->value,
                'delivery_time_unit' => $price->delivery_time_unit?->value,
                'delivery_time_value' => $price->delivery_time_value,
                'vat_status' => (bool) $price->vat_status,
            ],
        ];
    }

    public function calculateLineTotal(?float $price, int $quantity, float $discount): float
    {
        if ($price === null) {
            return 0.0;
        }

        $subtotal = $price * $quantity;
        $discountValue = $subtotal * ($discount / 100);

        return round($subtotal - $discountValue, 2);
    }
}


