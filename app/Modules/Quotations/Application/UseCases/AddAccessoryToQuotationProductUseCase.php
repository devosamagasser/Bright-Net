<?php

namespace App\Modules\Quotations\Application\UseCases;

use Illuminate\Validation\ValidationException;
use App\Modules\Quotations\Application\DTOs\QuotationAccessoryInput;
use App\Modules\Products\Domain\Models\Product;
use App\Modules\Products\Domain\Models\ProductAccessory;
use App\Modules\Products\Domain\Repositories\ProductRepositoryInterface;
use App\Modules\Products\Domain\ValueObjects\AccessoryType;
use App\Modules\Quotations\Domain\Models\{
    Quotation,
    QuotationProduct,
};
use App\Modules\Quotations\Domain\Repositories\QuotationRepositoryInterface;
use App\Modules\Quotations\Domain\ValueObjects\QuotationStatus;

class AddAccessoryToQuotationProductUseCase
{
    public function __construct(
        private readonly QuotationRepositoryInterface $quotations,
        private readonly ProductRepositoryInterface $products,
    ) {
    }

    public function handle(QuotationProduct $item, QuotationAccessoryInput $input, int $supplierId): Quotation
    {
        $quotation = $item->quotation;

        $this->assertEditable($quotation, $supplierId);

        $accessory = $this->products->find($input->accessoryId);

        if ($accessory === null) {
            throw ValidationException::withMessages([
                'accessory_id' => trans('validation.exists', ['attribute' => 'accessory']),
            ]);
        }

        $accessory->loadMissing('family.supplier');

        $accessorySupplierId = $accessory->family?->supplier?->getKey();

        if ($accessorySupplierId === null || (int) $accessorySupplierId !== $supplierId) {
            throw ValidationException::withMessages([
                'accessory_id' => trans('apiMessages.forbidden'),
            ]);
        }

        $item->loadMissing('product.accessories');

        $product = $item->product;

        if ($product === null) {
            throw ValidationException::withMessages([
                'accessory_id' => trans('validation.in', ['attribute' => 'accessory']),
            ]);
        }

        $typeValue = $input->type;

        if ($typeValue === null) {
            throw ValidationException::withMessages([
                'accessory_type' => trans('validation.required', ['attribute' => 'accessory type']),
            ]);
        }

        $type = AccessoryType::tryFrom($typeValue);

        if ($type === null) {
            throw ValidationException::withMessages([
                'accessory_type' => trans('validation.in', ['attribute' => 'accessory type']),
            ]);
        }

        $this->assertAccessoryIsOptionalForProduct($product, $accessory, $type);

        $this->quotations->addAccessory($item, $accessory, $input->attributes());

        return $this->quotations->refreshTotals($quotation);
    }

    private function assertEditable(Quotation $quotation, int $supplierId): void
    {
        if ((int) $quotation->supplier_id !== $supplierId || $quotation->status !== QuotationStatus::DRAFT) {
            throw ValidationException::withMessages([
                'quotation' => trans('apiMessages.forbidden'),
            ]);
        }
    }

    private function assertAccessoryIsOptionalForProduct(Product $product, Product $accessory, AccessoryType $type): void
    {
        if ($type !== AccessoryType::OPTIONAL) {
            throw ValidationException::withMessages([
                'accessory_type' => trans('apiMessages.forbidden'),
            ]);
        }

        $linkedAccessory = $product->accessories
            ->first(static function (ProductAccessory $definition) use ($accessory): bool {
                return (int) $definition->accessory_id === (int) $accessory->getKey();
            });

        if ($linkedAccessory === null || $linkedAccessory->accessory_type !== AccessoryType::OPTIONAL) {
            throw ValidationException::withMessages([
                'accessory_id' => trans('validation.in', ['attribute' => 'accessory']),
            ]);
        }
    }
}
