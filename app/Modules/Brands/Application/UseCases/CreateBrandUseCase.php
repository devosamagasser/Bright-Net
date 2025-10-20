<?php

namespace App\Modules\Brands\Application\UseCases;

use App\Modules\Brands\Application\DTOs\{BrandData, BrandInput};
use App\Modules\Brands\Domain\Repositories\BrandRepositoryInterface;

class CreateBrandUseCase
{
    public function __construct(
        private readonly BrandRepositoryInterface $repository,
    ) {
    }

    public function handle(BrandInput $input): BrandData
    {
        $brand = $this->repository->create(
            $input->attributes,
            $input->solutions,
        );

        return BrandData::fromModel($brand);
    }
}
