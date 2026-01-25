<?php

namespace App\Modules\Specifications\Application\UseCases;

use App\Modules\Specifications\Application\Concerns\AssertsSpecificationEditable;
use App\Modules\Specifications\Application\DTOs\SpecificationItemUpdateInput;
use App\Modules\Specifications\Domain\Models\{
    Specification,
    SpecificationItem
};
use App\Modules\Specifications\Domain\Repositories\SpecificationRepositoryInterface;
use App\Modules\SpecificationLogs\Domain\Services\SpecificationActivityService;

class UpdateSpecificationItemUseCase
{
    use AssertsSpecificationEditable;

    public function __construct(
        private readonly SpecificationRepositoryInterface $specifications,
        private readonly SpecificationActivityService $activityService,
    ) {
    }

    public function handle(SpecificationItem $item, SpecificationItemUpdateInput $input, int $companyId): Specification
    {
        $specification = $item->specification;

        $this->assertEditable($specification, $companyId);

        $oldData = $item->toArray();

        $updatedItem = $this->specifications->updateItem(
            $item,
            $input->attributes()
        );

        $this->activityService->log(
            model: $item,
            activityType: 'update',
            oldObject: $oldData,
            newObject: $updatedItem->toArray(),
        );

        return $this->specifications->loadRelations($specification);
    }
}


