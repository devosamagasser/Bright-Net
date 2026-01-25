<?php

namespace App\Modules\Specifications\Domain\Services;

use Illuminate\Support\Facades\DB;
use App\Modules\Specifications\Domain\Models\{
    Specification,
    SpecificationItem
};

class SpecificationNumberingService
{
    /**
     * Generate specification reference like S260120-0001.
     */
    public function generateReference(): string
    {
        $prefix = 'S' . now()->format('ymd');

        $sequence = (int) (Specification::query()
            ->where('reference', 'like', $prefix . '%')
            ->count() + 1);

        return sprintf('%s-%04d', $prefix, $sequence);
    }

    /**
     * Get next item position and item_ref for a specification.
     *
     * @return array{position: int, item_ref: string}
     */
    public function getNextItemInfo(Specification $specification): array
    {
        $result = DB::table('specification_products')
            ->where('specification_id', $specification->getKey())
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

    public function generateItemReference(Specification $specification): string
    {
        $count = DB::table('specification_products')
            ->where('specification_id', $specification->getKey())
            ->whereNull('deleted_at')
            ->count();

        return sprintf('P-%03d', $count + 1);
    }

    public function generateAccessoryReference(SpecificationItem $item): string
    {
        $count = DB::table('specification_product_accessories')
            ->where('spec_product_id', $item->getKey())
            ->whereNull('deleted_at')
            ->count();

        return sprintf('%s-A%02d', $item->item_ref ?? 'P', $count + 1);
    }

    public function nextItemPosition(Specification $specification): int
    {
        $position = DB::table('specification_products')
            ->where('specification_id', $specification->getKey())
            ->whereNull('deleted_at')
            ->max('position');

        return $position === null ? 1 : ((int) $position + 1);
    }

    public function nextAccessoryPosition(SpecificationItem $item): int
    {
        $position = DB::table('specification_product_accessories')
            ->where('spec_product_id', $item->getKey())
            ->whereNull('deleted_at')
            ->max('position');

        return $position === null ? 1 : ((int) $position + 1);
    }
}


