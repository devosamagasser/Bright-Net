<?php

namespace App\Modules\Favourites\Application\UseCases;

use App\Modules\Favourites\Application\DTOs\CollectionData;
use App\Modules\Favourites\Domain\Models\FavouriteCollection;
use App\Modules\Favourites\Domain\Repositories\CollectionRepositoryInterface;
use Illuminate\Validation\ValidationException;

class RemoveProductFromCollectionUseCase
{
    public function __construct(
        private readonly CollectionRepositoryInterface $repository,
    ) {
    }

    public function handle(FavouriteCollection $collection, int $productId): CollectionData
    {
        if (! $this->repository->hasProduct($collection, $productId)) {
            throw ValidationException::withMessages([
                'product_id' => trans('apiMessages.product_not_in_collection'),
            ]);
        }

        $this->repository->removeProduct($collection, $productId);

        $collection->load('products');

        return CollectionData::fromModel($collection);
    }
}

