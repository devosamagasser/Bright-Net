<?php

namespace App\Modules\Quotations\Domain\Services;

use Illuminate\Support\Facades\DB;
use App\Modules\Quotations\Domain\Models\Quotation;
use App\Modules\Products\Domain\ValueObjects\AccessoryType;

class QuotationTotalsCalculator
{
    /**
     * Calculate totals using SQL aggregation for better performance
     * 
     * @return array{subtotal: float, discount_total: float, total: float}
     */
    public function calculateOptimized(int $quotationId): array
    {
        // Calculate products totals
        $productTotals = DB::table('quotation_products')
            ->where('quotation_id', $quotationId)
            ->whereNull('deleted_at')
            ->selectRaw('
                COALESCE(SUM(COALESCE(price, 0) * COALESCE(quantity, 0)), 0) as subtotal,
                COALESCE(SUM(COALESCE(price, 0) * COALESCE(quantity, 0) * COALESCE(discount, 0) / 100), 0) as discount_total
            ')
            ->first();

        // Calculate accessories totals (excluding INCLUDED type)
        $accessoryTotals = DB::table('quotation_product_accessories')
            ->where('quotation_id', $quotationId)
            ->whereNull('deleted_at')
            ->where('accessory_type', '!=', AccessoryType::INCLUDED->value)
            ->selectRaw('
                COALESCE(SUM(COALESCE(price, 0) * COALESCE(quantity, 0)), 0) as subtotal,
                COALESCE(SUM(COALESCE(price, 0) * COALESCE(quantity, 0) * COALESCE(discount, 0) / 100), 0) as discount_total
            ')
            ->first();

        $subtotal = (float) ($productTotals->subtotal ?? 0) + (float) ($accessoryTotals->subtotal ?? 0);
        $discountTotal = (float) ($productTotals->discount_total ?? 0) + (float) ($accessoryTotals->discount_total ?? 0);
        $total = $subtotal - $discountTotal;

        return [
            'subtotal' => round($subtotal, 2),
            'discount_total' => round($discountTotal, 2),
            'total' => round($total, 2),
        ];
    }

    /**
     * Legacy method - calculate totals using loaded relations
     * Keep for backwards compatibility or when relations are already loaded
     * 
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
