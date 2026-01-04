<?php

namespace App\Modules\Favourites\Application\UseCases;

use App\Modules\Favourites\Application\DTOs\CollectionData;
use App\Modules\Favourites\Domain\Models\FavouriteCollection;

class ShowCollectionUseCase
{
    public function handle(FavouriteCollection $collection): CollectionData
    {
        $collection->loadMissing('products');

        return CollectionData::fromModel($collection);
    }
}

