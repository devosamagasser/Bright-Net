<?php

namespace App\Modules\Products\Application\DTOs;

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

    public static function fromModel(ProductPrice $price): self
    {
        return new self([
            'id' => (int) $price->getKey(),
            'price' => (float) $price->price,
            'from' => $price->from,
            'to' => $price->to,
            'currency' => $price->currency?->value,
            'delivery_time_unit' => $price->delivery_time_unit?->value,
            'delivery_time_value' => $price->delivery_time_value,
            'vat_status' => $price->vat_status,
        ]);
    }
}
