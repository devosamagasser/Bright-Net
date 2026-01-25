<?php

namespace App\Modules\SolutionsCatalog\Application\UseCases;

use App\Modules\SolutionsCatalog\Application\DTOs\{SolutionData};
use App\Modules\SolutionsCatalog\Application\DTOs\SolutionInput;
use App\Modules\SolutionsCatalog\Domain\Repositories\SolutionRepositoryInterface;
use Illuminate\Database\Eloquent\ModelNotFoundException;

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

