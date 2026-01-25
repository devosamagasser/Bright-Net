<?php

namespace App\Modules\Quotations\Presentation\Resources;

use App\Modules\PriceRules\Domain\ValueObjects\PriceCurrency;
use App\Modules\Products\Domain\ValueObjects\{DeliveryTimeUnit};
use Illuminate\Http\Resources\Json\JsonResource;

class QuotationProductResource extends JsonResource
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

        $unitPrice = $this->price ?? 0;
        $quantity = $this->quantity ?? 0;
        $discount = $request->discount_applied ? $this->discount ?? 0 : 0;
        $lineSubtotal = $unitPrice * $quantity;
        $discountValue = $lineSubtotal * ($discount / 100);

        return [
            'id' => (int) $this->getKey(),
            'item_ref' => $this->item_ref,
            'position' => (int) $this->position,
            'product_id' => $this->product_id,
            'product_code' => $this->product_code,
            'product_name' => $this->product_name,
            'product_description' => $this->product_description,
            'product_origin' => $this->product_origin,
            'brand_id' => $this->brand_id,
            'brand_name' => $this->brand_name,
            'notes' => $this->notes,
            'bill' => [
                'quantity' => (int) $quantity,
                'price' => $this->price !== null ? (float) $this->price : null,
                'discount' => (float) $discount,
                'discount_amount' => round($discountValue, 2),
                'subtotal' => round($lineSubtotal, 2),
                'total' => $request->discount_applied ? (float) $this->total : round($lineSubtotal, 2),
                'currency' => $currency,
                'vat_included' => (bool) $this->vat_included,
                'delivery_time' =>  $this->delivery_time_value . ' ' . $deliveryUnit,
            ],
            'accessories' => QuotationAccessoryResource::collection(
                $this->whenLoaded('accessories')
            ),
        ];
    }
}
