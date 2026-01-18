<?php

namespace App\Modules\Quotations\Application\UseCases;

use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use App\Modules\Products\Domain\Models\Product;
use App\Modules\Products\Domain\Models\ProductAccessory;
use App\Modules\Products\Domain\ValueObjects\AccessoryType;
use App\Modules\QuotationLogs\Domain\Services\ActivityService;
use App\Modules\Quotations\Application\Concerns\AssertsQuotationEditable;
use App\Modules\Quotations\Application\DTOs\QuotationProductInput;
use App\Modules\Quotations\Domain\Models\{Quotation, QuotationProduct};
use App\Modules\Products\Domain\Repositories\ProductRepositoryInterface;
use App\Modules\QuotationLogs\Domain\ValueObjects\QuotationActivityType;
use App\Modules\Quotations\Domain\Repositories\QuotationRepositoryInterface;

class ReplaceQuotationProductUseCase
{
    use AssertsQuotationEditable;

    public function __construct(
        private readonly QuotationRepositoryInterface $quotations,
        private readonly ProductRepositoryInterface $products,
        private readonly ActivityService $activityService,
    ) {
    }

    public function handle(QuotationProduct $current, QuotationProductInput $input, int $supplierId): Quotation
    {
        return DB::transaction(function () use ($current, $input, $supplierId) {
            $quotation = $current->quotation;

            $this->assertEditable($quotation, $supplierId);

            $replacement = $this->products->find($input->productId);

            if ($replacement === null) {
                throw ValidationException::withMessages([
                    'product_id' => trans('validation.exists', ['attribute' => 'product']),
                ]);
            }

            // Eager load all needed relations at once
            $replacement->loadMissing([
                'family.supplier',
                'family.subcategory.department.solution',
                'brand',
                'accessories.accessory.prices',
                'accessories.accessory.family.subcategory.department.solution',
                'accessories.accessory.family.supplier.company',
                'accessories.accessory.brand',
                'prices',
                'translations',
            ]);

            $replacementSupplierId = $replacement->family?->supplier?->getKey();

            if ($replacementSupplierId === null || (int) $replacementSupplierId !== $supplierId) {
                throw ValidationException::withMessages([
                    'product_id' => trans('apiMessages.forbidden'),
                ]);
            }

            $accessories = $this->prepareAccessories($replacement, $input->accessories(), $supplierId);

            $newItem = $this->quotations->replaceProduct($current, $replacement, $input->attributes(), $accessories);

            $this->activityService->log(
                model: $current,
                activityType: QuotationActivityType::DELETE,
            );

            $this->activityService->log(
                model: $newItem,
                activityType: QuotationActivityType::CREATE,
            );

            return $this->quotations->refreshTotals($quotation);
        });
    }

    private function prepareAccessories(Product $product, array $accessoryInputs, int $supplierId): array
    {
        $accessories = [];

        foreach ($accessoryInputs as $accessoryInput) {
            $accessory = $this->products->find($accessoryInput->accessoryId);

            if ($accessory === null) {
                throw ValidationException::withMessages([
                    'accessories.*.accessory_id' => trans('validation.exists', ['attribute' => 'accessory']),
                ]);
            }

            $accessory->loadMissing([
                'family.supplier',
                'family.subcategory.department.solution',
                'brand',
                'prices',
                'translations',
            ]);

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

        return $accessories;
    }

    private function assertAccessoryIsOptionalForProduct(Product $product, Product $accessory, AccessoryType $type): void
    {
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
