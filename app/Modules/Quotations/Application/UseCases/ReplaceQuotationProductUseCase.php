<?php

namespace App\Modules\Quotations\Application\UseCases;

use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use App\Modules\Products\Domain\Models\Product;
use App\Modules\Products\Domain\Models\ProductAccessory;
use App\Modules\Products\Domain\ValueObjects\AccessoryType;
use App\Modules\QuotationLogs\Domain\Services\ActivityService;
use App\Modules\Quotations\Domain\ValueObjects\QuotationStatus;
use App\Modules\Quotations\Application\DTOs\QuotationProductInput;
use App\Modules\Quotations\Domain\Models\{Quotation, QuotationProduct};
use App\Modules\Products\Domain\Repositories\ProductRepositoryInterface;
use App\Modules\QuotationLogs\Domain\ValueObjects\QuotationActivityType;
use App\Modules\Quotations\Domain\Repositories\QuotationRepositoryInterface;

class ReplaceQuotationProductUseCase
{
    public function __construct(
        private readonly QuotationRepositoryInterface $quotations,
        private readonly ProductRepositoryInterface $products,
        private readonly ActivityService $activityService,
    ) {
    }

    public function handle(QuotationProduct $current, QuotationProductInput $input, int $supplierId): Quotation
    {
        return DB::transaction(function () use ($current, $input, $supplierId, &$quotation) {
            $quotation = $current->quotation;

            $this->assertEditable($quotation, $supplierId);

            $replacement = $this->products->find($input->productId);

            if ($replacement === null) {
                throw ValidationException::withMessages([
                    'product_id' => trans('validation.exists', ['attribute' => 'product']),
                ]);
            }

            $replacement->loadMissing(['family.supplier', 'accessories']);

            $replacementSupplierId = $replacement->family?->supplier?->getKey();

            if ($replacementSupplierId === null || (int) $replacementSupplierId !== $supplierId) {
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

                $this->assertAccessoryIsOptionalForProduct($replacement, $accessory, $type);

                $attributes = $accessoryInput->attributes();

                if (! Arr::exists($attributes, 'accessory_type')) {
                    $attributes['accessory_type'] = $type->value;
                }

                $accessories[] = [
                    'product' => $accessory,
                    'attributes' => $attributes,
                ];
            }

            $replacement = $this->quotations->replaceProduct($current, $replacement, $input->attributes(), $accessories);

            $this->activityService->log(
                model: $current,
                activityType: QuotationActivityType::DELETE,
            );

            $this->activityService->log(
                model: $replacement,
                activityType: QuotationActivityType::CREATE,
            );

            return $this->quotations->refreshTotals($quotation);
        });
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
        // if ($type !== AccessoryType::OPTIONAL) {
        //     throw ValidationException::withMessages([
        //         'accessories.*.accessory_type' => trans('apiMessages.forbidden'),
        //     ]);
        // }

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
