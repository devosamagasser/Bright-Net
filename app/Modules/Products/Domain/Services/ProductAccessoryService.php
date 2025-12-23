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
        if ($accessories === []) {
            return;
        }

        $rows = [];

        $product->accessories()->delete();

        foreach ($accessories as $index => $accessory) {
            $code = Arr::get($accessory, 'code');
            $type = Arr::get($accessory, 'type');
            $quantity = Arr::get($accessory, 'quantity');

            if (! is_string($code) || ! is_string($type)) {
                continue;
            }

            $accessoryProduct = Product::query()->where('code', $code)->first();
            if ($accessoryProduct === null) {
                continue;
            }

            if ($accessoryProduct->getKey() === $product->getKey()) {
                continue;
            }

            $rows[] = [
                'product_id' => $product->getKey(),
                'accessory_id' => $accessoryProduct->getKey(),
                'accessory_type' => AccessoryType::from($type),
                'quantity' => $quantity,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        DB::table('product_accessories')
            ->insert($rows);
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
}
