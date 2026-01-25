<?php

namespace App\Modules\Quotations\Presentation\Resources;

use App\Modules\PriceRules\Domain\ValueObjects\PriceCurrency;
use App\Modules\Products\Domain\ValueObjects\{AccessoryType, DeliveryTimeUnit};
use Illuminate\Http\Resources\Json\JsonResource;

class QuotationAccessoryResource extends JsonResource
{
    /**
     * @param  \Illuminate\Http\Request  $request
     * @return array<string, mixed>
     */
    public function toArray($request): array
    {
        $currency = $this->currency instanceof PriceCurrency
            ? $this->currency->value
            : $this->currency;

        $deliveryUnit = $this->delivery_time_unit instanceof DeliveryTimeUnit
            ? $this->delivery_time_unit->value
            : $this->delivery_time_unit;

        $type = $this->accessory_type instanceof AccessoryType
            ? $this->accessory_type->value
            : $this->accessory_type;

        $unitPrice = $this->price ?? 0;
        $quantity = $this->quantity ?? 0;
        $discount = $this->discount ?? 0;
        $lineSubtotal = $unitPrice * $quantity;
        $discountValue = $lineSubtotal * ($discount / 100);

        return [
            'id' => (int) $this->getKey(),
            'item_ref' => $this->item_ref,
            'position' => (int) $this->position,
            'type' => $type,
            'snapshot' => $this->product_snapshot,
            'roots' => $this->roots_snapshot,
            'notes' => $this->notes,
            'bill' => [
                'quantity' => (int) $quantity,
                'price' => $this->price !== null ? (float) $this->price : null,
                // 'list_price' => $this->list_price !== null ? (float) $this->list_price : null,
                'discount' => (float) $discount,
                'discount_amount' => round($discountValue, 2),
                'subtotal' => round($lineSubtotal, 2),
                'total' => (float) $this->total,
                'currency' => $currency,
                'vat_included' => (bool) $this->vat_included,
                'delivery_time' => [
                    'unit' => $deliveryUnit,
                    'value' => $this->delivery_time_value,
                ],
            ],
            // 'product' => [
                // 'id' => $this->product_id,
                // 'code' => $this->product_code,
                // 'name' => $this->product_name,
                // 'description' => $this->product_description,
            // ],
            // 'price_snapshot' => $this->price_snapshot,
        ];
    }
}
