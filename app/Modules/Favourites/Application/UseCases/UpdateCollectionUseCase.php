<?php

namespace App\Modules\Favourites\Application\UseCases;

use App\Modules\Favourites\Application\DTOs\CollectionData;
use App\Modules\Favourites\Application\DTOs\CollectionInput;
use App\Modules\Favourites\Domain\Models\FavouriteCollection;
use App\Modules\Favourites\Domain\Repositories\CollectionRepositoryInterface;

class UpdateCollectionUseCase
{
    public function __construct(
        private readonly CollectionRepositoryInterface $repository,
    ) {
    }

    public function handle(FavouriteCollection $collection, CollectionInput $input): CollectionData
    {
        $collection = $this->repository->update($collection, $input->attributes);

        return CollectionData::fromModel($collection);
    }
}

