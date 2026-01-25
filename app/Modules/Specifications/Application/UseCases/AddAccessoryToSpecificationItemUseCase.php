<?php

namespace App\Modules\Specifications\Application\UseCases;

use App\Modules\Specifications\Application\Concerns\AssertsSpecificationEditable;
use App\Modules\Specifications\Application\DTOs\SpecificationAccessoryInput;
use App\Modules\Specifications\Domain\Models\{
    Specification,
    SpecificationItem,
    SpecificationItemAccessory
};
use App\Modules\Specifications\Domain\Repositories\SpecificationRepositoryInterface;
use App\Modules\Products\Domain\Repositories\ProductRepositoryInterface;
use App\Modules\SpecificationLogs\Domain\Services\SpecificationActivityService;

class AddAccessoryToSpecificationItemUseCase
{
    use AssertsSpecificationEditable;

    public function __construct(
        private readonly SpecificationRepositoryInterface $specifications,
        private readonly ProductRepositoryInterface $products,
        private readonly SpecificationActivityService $activityService,
    ) {
    }

    public function handle(SpecificationItem $item, SpecificationAccessoryInput $input, int $companyId): Specification
    {
        $specification = $item->specification;

        $this->assertEditable($specification, $companyId);

        $accessory = $item->product->accessories()->with([
            'accessory.family',
            'accessory.brand',
            'accessory.translations',
        ])->where('accessory_id', $input->accessoryId)
        ->firstOrFail()
        ->accessory;
//        $accessory = $this->products->find($input->accessoryId, relations: [
//            'family',
//            'brand',
//            'translations',
//        ]);

        $accessoryItem = $this->specifications->addAccessory(
            $item,
            $accessory,
            $input->attributes()
        );

        $this->activityService->log(
            model: $accessoryItem,
            activityType: 'create',
        );

        return $this->specifications->loadRelations($specification);
    }
}


