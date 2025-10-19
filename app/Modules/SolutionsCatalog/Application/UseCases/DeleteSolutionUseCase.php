<?php

namespace App\Modules\SolutionsCatalog\Application\UseCases;

use App\Modules\SolutionsCatalog\Domain\Repositories\SolutionRepositoryInterface;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class DeleteSolutionUseCase
{
    public function __construct(
        private readonly SolutionRepositoryInterface $repository,
    ) {
    }

    /**
     * Delete a solution by its id.
     */
    public function handle(int $solutionId): void
    {
        $solution = $this->repository->find($solutionId);

        if (! $solution) {
            throw new ModelNotFoundException();
        }

        $this->repository->delete($solution);
    }
}

