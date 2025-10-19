<?php

namespace App\Modules\Geography\Application\UseCases;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Modules\Geography\Application\DTOs\{RegionData, RegionInput};
use App\Modules\Geography\Domain\Repositories\RegionRepositoryInterface;

class UpdateRegionUseCase
{
    public function __construct(
        private readonly RegionRepositoryInterface $repository,
    ) {
    }

    public function handle(int $regionId, RegionInput $input): RegionData
    {
        $region = $this->repository->find($regionId);

        if (! $region) {
            throw new ModelNotFoundException();
        }

        $region = $this->repository->update($region, $input->attributes);

        return RegionData::fromModel($region);
    }
}
