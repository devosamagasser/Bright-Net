<?php

namespace App\Modules\Geography\Application\UseCases;

use App\Modules\Geography\Application\DTOs\{RegionData, RegionInput};
use App\Modules\Geography\Domain\Repositories\RegionRepositoryInterface;

class CreateRegionUseCase
{
    public function __construct(
        private readonly RegionRepositoryInterface $repository,
    ) {
    }

    public function handle(RegionInput $input): RegionData
    {
        $region = $this->repository->create($input->attributes);

        return RegionData::fromModel($region);
    }
}
