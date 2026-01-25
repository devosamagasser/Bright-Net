<?php

namespace App\Modules\SolutionsCatalog\Application\UseCases;

use App\Modules\Brands\Domain\Repositories\BrandRepositoryInterface;
use App\Modules\SolutionsCatalog\Application\DTOs\SolutionBrandData;
use App\Modules\SolutionsCatalog\Domain\Repositories\SolutionRepositoryInterface;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Collection;

class ListSolutionBrandsUseCase
{
    public function __construct(
        private readonly SolutionRepositoryInterface $solutionRepository,
        private readonly BrandRepositoryInterface $brandRepository,
    ) {
    }

    public function handle(int $solutionId): Collection
    {
        if (! $this->solutionRepository->exists($solutionId)) {
            throw new ModelNotFoundException();
        }

        $brands = $this->brandRepository->getBySolution($solutionId);

        return SolutionBrandData::collection($brands);
    }
}
