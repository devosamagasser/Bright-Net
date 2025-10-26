<?php

namespace App\Modules\DataSheets\Application\UseCases;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use App\Modules\DataSheets\Application\DTOs\DataTemplateData;
use App\Modules\DataSheets\Domain\Repositories\DataTemplateRepositoryInterface;

class ListDataTemplatesUseCase
{
    public function __construct(
        private readonly DataTemplateRepositoryInterface $repository,
    ) {
    }

    public function handle(int $perPage = 15): LengthAwarePaginator
    {
        $paginator = $this->repository->paginate($perPage);

        $paginator->setCollection(
            DataTemplateData::collection($paginator->getCollection())
        );

        return $paginator;
    }
}
