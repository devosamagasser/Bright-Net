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
use App\Modules\QuotationLogs\Domain\Services\ActivityService;
use App\Modules\QuotationLogs\Domain\ValueObjects\QuotationActivityType;

class AddAccessoryToQuotationProductUseCase
{
    public function __construct(
        private readonly QuotationRepositoryInterface $quotations,
        private readonly ProductRepositoryInterface $products,
        private readonly ActivityService $activityService,
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

        $this->assertAccessoryIsOptionalForProduct($product, $accessory);

        $accessory = $this->quotations->addAccessory($item, $accessory, $input->attributes());

        $this->activityService->log(
            model: $accessory,
            activityType: QuotationActivityType::CREATE,
        );
        
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

    private function assertAccessoryIsOptionalForProduct(Product $product, Product $accessory): void
    {
        $linkedAccessory = $product->accessories
            ->first(static function (ProductAccessory $definition) use ($accessory): bool {
                return (int) $definition->accessory_id === (int) $accessory->getKey();
            });

        if ($linkedAccessory === null) {
            throw ValidationException::withMessages([
                'accessory_id' => trans('validation.in', ['attribute' => 'accessory']),
            ]);
        }
    }
}
