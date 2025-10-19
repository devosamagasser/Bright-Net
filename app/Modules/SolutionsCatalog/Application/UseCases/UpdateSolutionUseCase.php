<?php

namespace App\Modules\SolutionsCatalog\Application\UseCases;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Modules\SolutionsCatalog\Application\DTOs\{SolutionData, SolutionInput};
use App\Modules\SolutionsCatalog\Domain\Repositories\SolutionRepositoryInterface;

class UpdateSolutionUseCase
{
    public function __construct(
        private readonly SolutionRepositoryInterface $repository,
    ) {
    }

    /**
     * Update an existing solution.
     */
    public function handle(int $solutionId, SolutionInput $input): SolutionData
    {
        $solution = $this->repository->find($solutionId);

        if (! $solution) {
            throw new ModelNotFoundException();
        }

        $solution = $this->repository->update(
            $solution,
            attributes: $input->attributes,
            translations: $input->translations,
        );

        return SolutionData::fromModel($solution->load('departments.subcategories'));
    }
}

