<?php

namespace App\Modules\Favourites\Application\UseCases;

use App\Modules\Favourites\Application\DTOs\CollectionData;
use App\Modules\Favourites\Domain\Models\FavouriteCollection;
use App\Modules\Favourites\Domain\Repositories\CollectionRepositoryInterface;
use App\Modules\Products\Domain\Repositories\ProductRepositoryInterface;
use Illuminate\Validation\ValidationException;

class AddProductToCollectionUseCase
{
    public function __construct(
        private readonly CollectionRepositoryInterface $collectionRepository,
        private readonly ProductRepositoryInterface $productRepository,
    ) {
    }

    public function handle(FavouriteCollection $collection, int $productId): CollectionData
    {
        $product = $this->productRepository->find($productId);

        if ($product === null) {
            throw ValidationException::withMessages([
                'product_id' => trans('validation.exists', ['attribute' => 'product']),
            ]);
        }

        if ($this->collectionRepository->hasProduct($collection, $productId)) {
            throw ValidationException::withMessages([
                'product_id' => trans('apiMessages.product_already_in_collection'),
            ]);
        }

        $this->collectionRepository->addProduct($collection, $productId);

        $collection->load('products');

        return CollectionData::fromModel($collection);
    }
}

