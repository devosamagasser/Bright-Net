<?php

namespace App\Modules\Favourites\Application\UseCases;

use App\Modules\Favourites\Domain\Models\FavouriteCollection;
use App\Modules\Favourites\Domain\Repositories\CollectionRepositoryInterface;

class DeleteCollectionUseCase
{
    public function __construct(
        private readonly CollectionRepositoryInterface $repository,
    ) {
    }

    public function handle(FavouriteCollection $collection): void
    {
        $this->repository->delete($collection);
    }
}

