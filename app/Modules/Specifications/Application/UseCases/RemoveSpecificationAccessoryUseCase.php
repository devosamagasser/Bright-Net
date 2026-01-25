<?php

namespace App\Modules\Specifications\Application\UseCases;

use App\Modules\Specifications\Application\Concerns\AssertsSpecificationEditable;
use App\Modules\Specifications\Domain\Models\{
    Specification,
    SpecificationItemAccessory
};
use App\Modules\Specifications\Domain\Repositories\SpecificationRepositoryInterface;
use App\Modules\SpecificationLogs\Domain\Services\SpecificationActivityService;

class RemoveSpecificationAccessoryUseCase
{
    use AssertsSpecificationEditable;

    public function __construct(
        private readonly SpecificationRepositoryInterface $specifications,
        private readonly SpecificationActivityService $activityService,
    ) {
    }

    public function handle(SpecificationItemAccessory $accessory, int $companyId): Specification
    {
        $specification = $accessory->specification;

        $this->assertEditable($specification, $companyId);

        $oldData = $accessory->toArray();

        $this->specifications->deleteAccessory($accessory);

        $this->activityService->log(
            model: $accessory,
            activityType: 'delete',
            oldObject: $oldData,
            newObject: null,
        );

        return $this->specifications->loadRelations($specification);
    }
}


