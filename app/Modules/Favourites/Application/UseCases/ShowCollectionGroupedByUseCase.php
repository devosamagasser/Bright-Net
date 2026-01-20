<?php

namespace App\Modules\Favourites\Application\UseCases;

use App\Modules\Favourites\Domain\Repositories\CollectionRepositoryInterface;
use App\Modules\Favourites\Domain\Services\RelationModelService;

class ShowCollectionGroupedByUseCase
{
    public function __construct(
        private readonly CollectionRepositoryInterface $repository,
        private readonly RelationModelService $modelService,
    )
    {
    }

    public function handle(int $collectionId, string $model, int $perpage = 15)
    {
        $relatedModels = $this->repository->getRelatedModelsForCollection($collectionId, $model, $perpage);
        return $relatedModels->setCollection(
            $this->modelService->modelData($model, $relatedModels->getCollection())
        );
    }
}
