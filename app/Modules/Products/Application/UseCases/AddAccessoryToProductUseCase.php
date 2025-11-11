<?php

namespace App\Modules\Products\Application\UseCases;

use Illuminate\Validation\ValidationException;
use App\Modules\Products\Application\DTOs\ProductData;
use App\Modules\Products\Domain\Models\Product;
use App\Modules\Products\Domain\Repositories\ProductRepositoryInterface;
use App\Modules\Products\Domain\ValueObjects\AccessoryType;

class AddAccessoryToProductUseCase
{
    public function __construct(private readonly ProductRepositoryInterface $products)
    {
    }

    /**
     * @param  array{accessory_id:int, accessory_type:string, quantity?:int|null}  $data
     */
    public function handle(Product $product, array $data, ?int $supplierId): ProductData
    {
        $product->loadMissing('family.supplier');

        $this->assertProductBelongsToSupplier($product, $supplierId);

        $accessory = $this->products->find((int) $data['accessory_id']);

        if ($accessory === null) {
            throw ValidationException::withMessages([
                'accessory_id' => trans('validation.exists', ['attribute' => 'accessory']),
            ]);
        }

        $accessory->loadMissing('family.supplier');

        $this->assertProductBelongsToSupplier($accessory, $supplierId, 'accessory_id');

        if ((int) $accessory->getKey() === (int) $product->getKey()) {
            throw ValidationException::withMessages([
                'accessory_id' => trans('validation.different', ['attribute' => 'accessory', 'other' => 'product']),
            ]);
        }

        $type = AccessoryType::tryFrom($data['accessory_type']);

        if ($type === null) {
            throw ValidationException::withMessages([
                'accessory_type' => trans('validation.in', ['attribute' => 'accessory type']),
            ]);
        }

        $quantity = $data['quantity'] ?? null;
        $quantityValue = $quantity !== null ? max(1, (int) $quantity) : null;

        $this->products->attachAccessory($product, $accessory, $type, $quantityValue);

        $fresh = $this->products->find((int) $product->getKey());

        if ($fresh === null) {
            throw ValidationException::withMessages([
                'product' => trans('validation.exists', ['attribute' => 'product']),
            ]);
        }

        return ProductData::fromModel($fresh);
    }

    private function assertProductBelongsToSupplier(Product $product, ?int $supplierId, string $attribute = 'product'): void
    {
        if ($supplierId === null) {
            return;
        }

        $productSupplierId = $product->family?->supplier?->getKey();

        if ($productSupplierId === null || (int) $productSupplierId !== $supplierId) {
            throw ValidationException::withMessages([
                $attribute => trans('apiMessages.forbidden'),
            ]);
        }
    }
}
