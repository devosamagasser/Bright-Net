<?php

namespace App\Modules\SolutionsCatalog\Application\UseCases;

use App\Modules\SolutionsCatalog\Application\DTOs\SolutionData;
use App\Modules\SolutionsCatalog\Domain\Repositories\SolutionRepositoryInterface;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class ShowSolutionUseCase
{
    public function __construct(
        private readonly SolutionRepositoryInterface $repository,
    ) {
    }

    /**
     * Retrieve a single solution DTO by its id.
     */
    public function handle(int $solutionId): SolutionData
    {
        $solution = $this->repository->find($solutionId);

        if (! $solution) {
            throw new ModelNotFoundException();
        }

        return SolutionData::fromModel($solution);
    }
}

