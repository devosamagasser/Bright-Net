<?php

namespace App\Modules\SolutionsCatalog\Application\UseCases;

use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Modules\Brands\Domain\Repositories\BrandRepositoryInterface;
use App\Modules\SolutionsCatalog\Domain\Repositories\SolutionRepositoryInterface;
use App\Modules\SolutionsCatalog\Application\DTOs\SolutionBrandData;

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
