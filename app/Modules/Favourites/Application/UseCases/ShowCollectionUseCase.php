<?php

namespace App\Modules\Favourites\Application\UseCases;

use App\Modules\Favourites\Application\DTOs\CollectionData;
use App\Modules\Favourites\Domain\Models\FavouriteCollection;
use App\Modules\Favourites\Domain\Repositories\CollectionRepositoryInterface;
use App\Modules\Favourites\Application\DTOs\ProductData;

class ShowCollectionUseCase
{

    public function __construct(
        private readonly CollectionRepositoryInterface $repository,
    )
    {
    }

    public function handle(int $collectionId, $perPage, $filter = []): CollectionData
    {
        $collection = $this->repository->find($collectionId, $perPage, $filter);
        $products = $collection['products']->setCollection(
            ProductData::collection($collection['products']->getCollection())
        );
        return CollectionData::fromModel(
            $collection['collection'],
            $products
        );
    }
}

