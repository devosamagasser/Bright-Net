<?php

namespace App\Modules\Quotations\Domain\Services;

use Illuminate\Support\Facades\DB;
use App\Modules\Quotations\Domain\Models\{
    Quotation,
    QuotationProduct,
};

class QuotationNumberingService
{
    /**
     * Generate quotation reference like Q260114-0001.
     */
    public function generateReference(): string
    {
        $prefix = 'Q' . now()->format('ymd');
        $sequence = (int) (Quotation::query()
            ->where('reference', 'like', $prefix . '%')
            ->count() + 1);

        return sprintf('%s-%04d', $prefix, $sequence);
    }

    /**
     * Get next item position and item_ref for a quotation in a single query.
     *
     * @return array{position: int, item_ref: string}
     */
    public function getNextItemInfo(Quotation $quotation): array
    {
        $result = DB::table('quotation_products')
            ->where('quotation_id', $quotation->getKey())
            ->whereNull('deleted_at')
            ->selectRaw('COALESCE(MAX(position), 0) + 1 as next_position, COUNT(*) + 1 as item_count')
            ->first();

        $position = (int) ($result->next_position ?? 1);
        $sequence = (int) ($result->item_count ?? 1);

        return [
            'position' => $position,
            'item_ref' => sprintf('P-%03d', $sequence),
        ];
    }

    public function generateItemReference(Quotation $quotation): string
    {
        $count = DB::table('quotation_products')
            ->where('quotation_id', $quotation->getKey())
            ->whereNull('deleted_at')
            ->count();

        return sprintf('P-%03d', $count + 1);
    }

    public function generateAccessoryReference(QuotationProduct $item): string
    {
        $count = DB::table('quotation_product_accessories')
            ->where('quotation_product_id', $item->getKey())
            ->whereNull('deleted_at')
            ->count();

        return sprintf('%s-A%02d', $item->item_ref ?? 'P', $count + 1);
    }

    public function nextItemPosition(Quotation $quotation): int
    {
        $position = DB::table('quotation_products')
            ->where('quotation_id', $quotation->getKey())
            ->whereNull('deleted_at')
            ->max('position');

        return $position === null ? 1 : ((int) $position + 1);
    }

    public function nextAccessoryPosition(QuotationProduct $item): int
    {
        $position = DB::table('quotation_product_accessories')
            ->where('quotation_product_id', $item->getKey())
            ->whereNull('deleted_at')
            ->max('position');

        return $position === null ? 1 : ((int) $position + 1);
    }
}



