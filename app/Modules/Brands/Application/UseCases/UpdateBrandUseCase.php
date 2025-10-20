<?php

namespace App\Modules\Brands\Application\UseCases;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Modules\Brands\Application\DTOs\{BrandData, BrandInput};
use App\Modules\Brands\Domain\Repositories\BrandRepositoryInterface;

class UpdateBrandUseCase
{
    public function __construct(
        private readonly BrandRepositoryInterface $repository,
    ) {
    }

    public function handle(int $brandId, BrandInput $input): BrandData
    {
        $brand = $this->repository->find($brandId);

        if (! $brand) {
            throw new ModelNotFoundException();
        }

        $brand = $this->repository->update(
            $brand,
            $input->attributes,
            $input->solutions,
        );

        return BrandData::fromModel($brand);
    }
}
