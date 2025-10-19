<?php

namespace App\Modules\Geography\Application\UseCases;

use App\Modules\Geography\Application\DTOs\RegionData;
use App\Modules\Geography\Domain\Repositories\RegionRepositoryInterface;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class ShowRegionUseCase
{
    public function __construct(
        private readonly RegionRepositoryInterface $repository,
    ) {
    }

    public function handle(int $regionId): RegionData
    {
        $region = $this->repository->find($regionId);

        if (! $region) {
            throw new ModelNotFoundException();
        }

        return RegionData::fromModel($region);
    }
}
