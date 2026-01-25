<?php

namespace App\Modules\Specifications\Application\UseCases;

use App\Modules\Specifications\Application\Concerns\AssertsSpecificationEditable;
use App\Modules\Specifications\Domain\Models\{
    Specification,
    SpecificationItem
};
use App\Modules\Specifications\Domain\Repositories\SpecificationRepositoryInterface;
use App\Modules\SpecificationLogs\Domain\Services\SpecificationActivityService;

class RemoveSpecificationItemUseCase
{
    use AssertsSpecificationEditable;

    public function __construct(
        private readonly SpecificationRepositoryInterface $specifications,
        private readonly SpecificationActivityService $activityService,
    ) {
    }

    public function handle(SpecificationItem $item, int $companyId): Specification
    {
        $specification = $item->specification;

        $this->assertEditable($specification, $companyId);

        $oldData = $item->toArray();

        $this->specifications->deleteItem($item);

        $this->activityService->log(
            model: $item,
            activityType: 'delete',
            oldObject: $oldData,
            newObject: null,
        );

        return $this->specifications->loadRelations($specification);
    }
}


