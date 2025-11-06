<?php

namespace App\Modules\Quotations\Domain\Services;

use App\Modules\Quotations\Domain\Models\Quotation;
use App\Modules\Products\Domain\ValueObjects\AccessoryType;

class QuotationTotalsCalculator
{
    /**
     * @return array{subtotal: float, discount_total: float, total: float}
     */
    public function calculate(Quotation $quotation): array
    {
        $subtotal = 0.0;
        $discountTotal = 0.0;

        foreach ($quotation->products as $item) {
            $unitPrice = $item->price ?? 0.0;
            $quantity = $item->quantity ?? 0;
            $discount = $item->discount ?? 0.0;

            $lineSubtotal = $unitPrice * $quantity;
            $lineDiscount = $lineSubtotal * ($discount / 100);

            $subtotal += $lineSubtotal;
            $discountTotal += $lineDiscount;

            foreach ($item->accessories as $accessory) {
                if ($accessory->accessory_type === AccessoryType::INCLUDED) {
                    continue;
                }

                $accPrice = $accessory->price ?? 0.0;
                $accQuantity = $accessory->quantity ?? 0;
                $accDiscount = $accessory->discount ?? 0.0;

                $accSubtotal = $accPrice * $accQuantity;
                $accDiscountTotal = $accSubtotal * ($accDiscount / 100);

                $subtotal += $accSubtotal;
                $discountTotal += $accDiscountTotal;
            }
        }

        $total = $subtotal - $discountTotal;

        return [
            'subtotal' => round($subtotal, 2),
            'discount_total' => round($discountTotal, 2),
            'total' => round($total, 2),
        ];
    }
}
