<?php

namespace App\Modules\SolutionsCatalog\Application\UseCases;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Modules\Brands\Domain\Repositories\BrandRepositoryInterface;
use App\Modules\SolutionsCatalog\Application\DTOs\SolutionBrandData;

class ShowSolutionBrandUseCase
{
    public function __construct(
        private readonly BrandRepositoryInterface $brandRepository,
    ) {
    }

    public function handle(int $solutionId, int $brandId): SolutionBrandData
    {
        $brand = $this->brandRepository->findForSolution($solutionId, $brandId);

        if (! $brand) {
            throw new ModelNotFoundException();
        }

        return SolutionBrandData::fromModel($brand);
    }
}
