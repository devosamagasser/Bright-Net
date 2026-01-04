<?php

namespace App\Modules\Favourites\Presentation\Http\Controllers;

use App\Modules\Favourites\Application\UseCases\AddProductToCollectionUseCase;
use App\Modules\Favourites\Application\UseCases\RemoveProductFromCollectionUseCase;
use App\Modules\Favourites\Domain\Models\FavouriteCollection;
use App\Modules\Favourites\Presentation\Http\Requests\AddProductRequest;
use App\Modules\Favourites\Presentation\Resources\CollectionResource;
use App\Modules\Products\Domain\Models\Product;
use App\Modules\Shared\Support\Helper\ApiResponse;
use Illuminate\Http\Response;

class CollectionProductController
{
    public function __construct(
        private readonly AddProductToCollectionUseCase $addProduct,
        private readonly RemoveProductFromCollectionUseCase $removeProduct,
    ) {
    }

    public function store(AddProductRequest $request, FavouriteCollection $collection)
    {
        $this->authorizeCollection($collection);

        $collectionData = $this->addProduct->handle(
            $collection,
            $request->productId()
        );

        return ApiResponse::success(
            CollectionResource::make($collectionData),
            __('apiMessages.created'),
            Response::HTTP_CREATED
        );
    }

    public function destroy(FavouriteCollection $collection, Product $product)
    {
        $this->authorizeCollection($collection);

        $collectionData = $this->removeProduct->handle(
            $collection,
            $product->getKey()
        );

        return ApiResponse::deleted();
    }

    private function authorizeCollection(FavouriteCollection $collection): void
    {
        $companyId = auth()->user()?->company_id;

        if ($companyId === null || $collection->company_id !== $companyId) {
            abort(403);
        }
    }
}

