<?php

namespace App\Modules\Geography\Application\UseCases;

use App\Modules\Geography\Domain\Repositories\RegionRepositoryInterface;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class DeleteRegionUseCase
{
    public function __construct(
        private readonly RegionRepositoryInterface $repository,
    ) {
    }

    public function handle(int $regionId): void
    {
        $region = $this->repository->find($regionId);

        if (! $region) {
            throw new ModelNotFoundException();
        }

        $this->repository->delete($region);
    }
}
