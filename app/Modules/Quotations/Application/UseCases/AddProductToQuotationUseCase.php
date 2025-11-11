<?php

namespace App\Modules\Quotations\Application\UseCases;

use Illuminate\Support\Arr;
use Illuminate\Validation\ValidationException;
use App\Modules\Quotations\Application\DTOs\QuotationProductInput;
use App\Modules\Products\Domain\Models\Product;
use App\Modules\Products\Domain\Models\ProductAccessory;
use App\Modules\Products\Domain\Repositories\ProductRepositoryInterface;
use App\Modules\Products\Domain\ValueObjects\AccessoryType;
use App\Modules\Quotations\Domain\Models\Quotation;
use App\Modules\Quotations\Domain\Repositories\QuotationRepositoryInterface;

class AddProductToQuotationUseCase
{
    public function __construct(
        private readonly QuotationRepositoryInterface $quotations,
        private readonly ProductRepositoryInterface $products,
    ) {
    }

    public function handle(int $supplierId, QuotationProductInput $input): Quotation
    {
        $quotation = $this->quotations->getOrCreateDraft($supplierId);

        $product = $this->products->find($input->productId);

        if ($product === null) {
            throw ValidationException::withMessages([
                'product_id' => trans('validation.exists', ['attribute' => 'product']),
            ]);
        }

        $product->loadMissing(['family.supplier', 'accessories']);

        $productSupplierId = $product->family?->supplier?->getKey();

        if ($productSupplierId === null || (int) $productSupplierId !== $supplierId) {
            throw ValidationException::withMessages([
                'product_id' => trans('apiMessages.forbidden'),
            ]);
        }

        $accessories = [];

        foreach ($input->accessories() as $accessoryInput) {
            $accessory = $this->products->find($accessoryInput->accessoryId);

            if ($accessory === null) {
                throw ValidationException::withMessages([
                    'accessories.*.accessory_id' => trans('validation.exists', ['attribute' => 'accessory']),
                ]);
            }

            $accessory->loadMissing('family.supplier');

            $accessorySupplierId = $accessory->family?->supplier?->getKey();

            if ($accessorySupplierId === null || (int) $accessorySupplierId !== $supplierId) {
                throw ValidationException::withMessages([
                    'accessories.*.accessory_id' => trans('apiMessages.forbidden'),
                ]);
            }

            $typeValue = $accessoryInput->type;

            if ($typeValue === null) {
                throw ValidationException::withMessages([
                    'accessories.*.accessory_type' => trans('validation.required', ['attribute' => 'accessory type']),
                ]);
            }

            $type = AccessoryType::tryFrom($typeValue);

            if ($type === null) {
                throw ValidationException::withMessages([
                    'accessories.*.accessory_type' => trans('validation.in', ['attribute' => 'accessory type']),
                ]);
            }

            $this->assertAccessoryIsOptionalForProduct($product, $accessory, $type);

            $attributes = $accessoryInput->attributes();

            if (! Arr::exists($attributes, 'accessory_type')) {
                $attributes['accessory_type'] = $type->value;
            }

            $accessories[] = [
                'product' => $accessory,
                'attributes' => $attributes,
            ];
        }

        $this->quotations->addProduct($quotation, $product, $input->attributes(), $accessories);

        return $this->quotations->refreshTotals($quotation);
    }

    private function assertAccessoryIsOptionalForProduct(Product $product, Product $accessory, AccessoryType $type): void
    {
        if ($type !== AccessoryType::OPTIONAL) {
            throw ValidationException::withMessages([
                'accessories.*.accessory_type' => trans('apiMessages.forbidden'),
            ]);
        }

        $linkedAccessory = $product->accessories
            ->first(static function (ProductAccessory $definition) use ($accessory): bool {
                return (int) $definition->accessory_id === (int) $accessory->getKey();
            });

        if ($linkedAccessory === null || $linkedAccessory->accessory_type !== AccessoryType::OPTIONAL) {
            throw ValidationException::withMessages([
                'accessories.*.accessory_id' => trans('validation.in', ['attribute' => 'accessory']),
            ]);
        }
    }
}
