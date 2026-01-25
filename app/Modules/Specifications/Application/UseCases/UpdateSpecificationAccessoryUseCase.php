<?php

namespace App\Modules\Specifications\Application\UseCases;

use App\Modules\Specifications\Application\Concerns\AssertsSpecificationEditable;
use App\Modules\Specifications\Application\DTOs\SpecificationAccessoryUpdateInput;
use App\Modules\Specifications\Domain\Models\{
    Specification,
    SpecificationItemAccessory
};
use App\Modules\Specifications\Domain\Repositories\SpecificationRepositoryInterface;
use App\Modules\SpecificationLogs\Domain\Services\SpecificationActivityService;

class UpdateSpecificationAccessoryUseCase
{
    use AssertsSpecificationEditable;

    public function __construct(
        private readonly SpecificationRepositoryInterface $specifications,
        private readonly SpecificationActivityService $activityService,
    ) {
    }

    public function handle(SpecificationItemAccessory $accessory, SpecificationAccessoryUpdateInput $input, int $companyId): Specification
    {
        $specification = $accessory->specification;

        $this->assertEditable($specification, $companyId);

        $oldData = $accessory->toArray();

        $updated = $this->specifications->updateAccessory(
            $accessory,
            $input->attributes()
        );

        $this->activityService->log(
            model: $accessory,
            activityType: 'update',
            oldObject: $oldData,
            newObject: $updated->toArray(),
        );

        return $this->specifications->loadRelations($specification);
    }
}


