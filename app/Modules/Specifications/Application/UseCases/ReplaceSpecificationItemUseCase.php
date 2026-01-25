<?php

namespace App\Modules\Specifications\Application\UseCases;

use Illuminate\Validation\ValidationException;
use App\Modules\Specifications\Application\Concerns\AssertsSpecificationEditable;
use App\Modules\Specifications\Application\DTOs\SpecificationItemInput;
use App\Modules\Specifications\Domain\Models\{
    Specification,
    SpecificationItem
};
use App\Modules\Specifications\Domain\Repositories\SpecificationRepositoryInterface;
use App\Modules\Products\Domain\Repositories\ProductRepositoryInterface;
use App\Modules\SpecificationLogs\Domain\Services\SpecificationActivityService;

class ReplaceSpecificationItemUseCase
{
    use AssertsSpecificationEditable;

    public function __construct(
        private readonly SpecificationRepositoryInterface $specifications,
        private readonly ProductRepositoryInterface $products,
        private readonly SpecificationActivityService $activityService,
    ) {
    }

    public function handle(SpecificationItem $current, SpecificationItemInput $input, int $companyId): Specification
    {
        $specification = $current->specification;

        $this->assertEditable($specification, $companyId);

        $replacement = $this->products->find($input->productId, relations: [
            'family',
            'brand',
            'translations',
        ]);

        if ($replacement === null) {
            throw ValidationException::withMessages([
                'product_id' => trans('validation.exists', ['attribute' => 'product']),
            ]);
        }

        $accessories = [];
        foreach ($input->accessories() as $accInput) {
            $accessoryProduct = $this->products->find($accInput->productId, relations: [
                'family',
                'brand',
                'translations',
            ]);

            if ($accessoryProduct === null) {
                throw ValidationException::withMessages([
                    'accessories.*.product_id' => trans('validation.exists', ['attribute' => 'accessory']),
                ]);
            }

            $accessories[] = [
                'product' => $accessoryProduct,
                'attributes' => $accInput->attributes(),
            ];
        }

        $newItem = $this->specifications->replaceItem(
            $current,
            $replacement,
            $input->attributes(),
            $accessories
        );

        // log delete of old item and create of new item
        $this->activityService->log(
            model: $current,
            activityType: 'delete',
        );

        $this->activityService->log(
            model: $newItem,
            activityType: 'create',
        );

        return $this->specifications->loadRelations($specification);
    }
}


