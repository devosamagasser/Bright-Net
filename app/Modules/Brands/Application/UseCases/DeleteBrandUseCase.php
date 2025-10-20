<?php

namespace App\Modules\Brands\Application\UseCases;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Modules\Brands\Domain\Repositories\BrandRepositoryInterface;

class DeleteBrandUseCase
{
    public function __construct(
        private readonly BrandRepositoryInterface $repository,
    ) {
    }

    public function handle(int $brandId): void
    {
        $brand = $this->repository->find($brandId);

        if (! $brand) {
            throw new ModelNotFoundException();
        }

        $this->repository->delete($brand);
    }
}
