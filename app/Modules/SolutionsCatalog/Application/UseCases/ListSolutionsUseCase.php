<?php

namespace App\Modules\SolutionsCatalog\Application\UseCases;

use App\Modules\SolutionsCatalog\Application\DTOs\SolutionData;
use App\Modules\SolutionsCatalog\Domain\Repositories\SolutionRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class ListSolutionsUseCase
{
    public function __construct(
        private readonly SolutionRepositoryInterface $repository,
    ) {
    }

    /**
     * Retrieve paginated solutions.
     */
    public function handle(int $perPage = 15): LengthAwarePaginator
    {
        $paginator = $this->repository->paginate($perPage);

        $paginator->setCollection(
            SolutionData::collection($paginator->getCollection())
        );

        return $paginator;
    }
}

