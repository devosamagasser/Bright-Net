<?php

namespace App\Modules\Brands\Application\UseCases;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Modules\Brands\Application\DTOs\BrandData;
use App\Modules\Brands\Domain\Repositories\BrandRepositoryInterface;

class ShowBrandUseCase
{
    public function __construct(
        private readonly BrandRepositoryInterface $repository,
    ) {
    }

    public function handle(int $brandId): BrandData
    {
        $brand = $this->repository->find($brandId);

        if (! $brand) {
            throw new ModelNotFoundException();
        }

        return BrandData::fromModel($brand);
    }
}
