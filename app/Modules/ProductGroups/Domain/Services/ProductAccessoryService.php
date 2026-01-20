<?php
namespace App\Modules\Products\Domain\Services;

use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use App\Modules\Products\Domain\Models\Product;
use App\Modules\Products\Domain\Models\ProductAccessory;
use App\Modules\Products\Domain\ValueObjects\AccessoryType;

class ProductAccessoryService
{
    /**
     * @param  array<string, mixed>  $accessories
     */
    public function syncAccessories(Product $product, array $accessories): void
    {
        // امسح القديم دايمًا (sync)
        $product->accessories()->delete();

        if ($accessories === []) {
            return;
        }
        $rows = $this->buildProductAccessoriesPayload($product, $accessories);

        if ($rows === []) {
            return;
        }

        DB::table('product_accessories')->insert($rows);
    }

    public function findAccessoryOfProduct(int $product_id, int $accessory_id): ?ProductAccessory
    {
        return ProductAccessory::query()
            ->with([
                'product',
                'accessory',
            ])
        ->where('product_id', $product_id)
        ->where('accessory_id', $accessory_id)
        ->first();
    }

    public function buildProductAccessoriesPayload($product, $accessories)
    {
        $codes = collect($accessories)
            ->pluck('code')
            ->filter(fn ($c) => is_string($c) && $c !== '')
            ->unique()
            ->values();

        $idsByCode = Product::whereIn('code', $codes)
            ->pluck('id', 'code'); // [code => id]

        $now = now();
        $rows = [];

        foreach ($accessories as $accessory) {
            $code = Arr::get($accessory, 'code');
            $type = Arr::get($accessory, 'type');
            $quantity = Arr::get($accessory, 'quantity', 1);
            $enumType = AccessoryType::tryFrom($type);
            $accessoryId = $idsByCode[$code] ?? null;

            if ($enumType === null || $accessoryId === null || $accessoryId === $product->getKey()) {
                continue;
            }

            $rows[] = [
                'product_id'      => $product->getKey(),
                'accessory_id'    => $accessoryId,
                'accessory_type'  => $enumType->value, // أو $enumType حسب نوع العمود
                'quantity'        => $quantity,
                'created_at'      => $now,
                'updated_at'      => $now,
            ];
        }
        return $rows;
    }

    public function buildQuotationAccessoriesPayload(Product $product, iterable $accessoriesInput): array
    {
        $inputs = collect($accessoriesInput);

        $requestedIds = $inputs
            ->pluck('accessoryId')
            ->map(fn ($id) => (int) $id)
            ->unique()
            ->values();

        if ($requestedIds->isEmpty()) {
            return [];
        }

        $pivotsByAccessoryId = $product->accessories()
            ->whereIn('accessory_id', $requestedIds)
            ->with([
                'accessory' => fn ($q) => $q->select('id', 'code'),
            ])
            ->get(['id', 'product_id', 'accessory_id', 'accessory_type', 'quantity'])
            ->keyBy('accessory_id');

        return $inputs->map(function ($accessoryInput) use ($pivotsByAccessoryId) {
            $accessoryId = (int) $accessoryInput->accessoryId;

            $pivot = $pivotsByAccessoryId->get($accessoryId);
            if ($pivot === null) {
                return null;
            }

            $attributes = $accessoryInput->attributes();

            if (! Arr::exists($attributes, 'accessory_type')) {
                $attributes['accessory_type'] = is_object($pivot->accessory_type)
                    ? $pivot->accessory_type->value
                    : $pivot->accessory_type;
            }
            return [
                'product'    => $pivot->accessory,
                'attributes' => $attributes,
            ];
        })
        ->filter()   // يشيل null
        ->values()
        ->all();

    }

}
