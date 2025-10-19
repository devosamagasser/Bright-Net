<?php

namespace App\Modules\Taxonomy\Application\UseCases;

use App\Modules\Taxonomy\Application\DTOs\ColorData;
use App\Modules\Taxonomy\Domain\Repositories\ColorRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class ListColorsUseCase
{
    public function __construct(
        private readonly ColorRepositoryInterface $repository,
    ) {
    }

    public function handle(int $perPage = 15): LengthAwarePaginator
    {
        $paginator = $this->repository->paginate($perPage);

        $paginator->setCollection(
            ColorData::collection($paginator->getCollection())
        );

        return $paginator;
    }
}
