<?php

namespace App\Modules\SolutionsCatalog\Application\UseCases;

use App\Modules\SolutionsCatalog\Application\DTOs\{SolutionData};
use App\Modules\SolutionsCatalog\Application\DTOs\SolutionInput;
use App\Modules\SolutionsCatalog\Domain\Repositories\SolutionRepositoryInterface;

class CreateSolutionUseCase
{
    public function __construct(
        private readonly SolutionRepositoryInterface $repository,
    ) {
    }

    /**
     * Persist a new solution.
     */
    public function handle(SolutionInput $input): SolutionData
    {
        $solution = $this->repository->create(
            attributes: $input->attributes,
            translations: $input->translations,
        );

        return SolutionData::fromModel($solution->load('departments.subcategories'));
    }
}

