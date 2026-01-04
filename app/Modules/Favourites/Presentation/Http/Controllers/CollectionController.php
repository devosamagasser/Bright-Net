<?php

namespace App\Modules\Favourites\Presentation\Http\Controllers;

use App\Modules\Favourites\Application\UseCases\CreateCollectionUseCase;
use App\Modules\Favourites\Application\UseCases\DeleteCollectionUseCase;
use App\Modules\Favourites\Application\UseCases\ListCollectionsUseCase;
use App\Modules\Favourites\Application\UseCases\ShowCollectionUseCase;
use App\Modules\Favourites\Application\UseCases\UpdateCollectionUseCase;
use App\Modules\Favourites\Domain\Models\FavouriteCollection;
use App\Modules\Favourites\Presentation\Http\Requests\StoreCollectionRequest;
use App\Modules\Favourites\Presentation\Http\Requests\UpdateCollectionRequest;
use App\Modules\Favourites\Presentation\Resources\CollectionResource;
use App\Modules\Shared\Support\Helper\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class CollectionController
{
    public function __construct(
        private readonly ListCollectionsUseCase $listCollections,
        private readonly ShowCollectionUseCase $showCollection,
        private readonly CreateCollectionUseCase $createCollection,
        private readonly UpdateCollectionUseCase $updateCollection,
        private readonly DeleteCollectionUseCase $deleteCollection,
    ) {
    }

    public function index(Request $request)
    {
        $companyId = $this->authenticatedCompanyId();

        if ($companyId === null) {
            return ApiResponse::unauthorized();
        }

//        $perPage = (int) $request->query('per_page', 15);
//        $perPage = max(1, min(100, $perPage));

        $collections = $this->listCollections->handle($companyId);

        return ApiResponse::success(
            CollectionResource::collection($collections)
        );
    }

    public function store(StoreCollectionRequest $request)
    {
        $companyId = $this->authenticatedCompanyId();

        if ($companyId === null) {
            return ApiResponse::unauthorized();
        }

        $collection = $this->createCollection->handle(
            $request->toCollectionInput(),
            $companyId
        );

        return ApiResponse::success(
            CollectionResource::make($collection),
            __('apiMessages.created'),
            Response::HTTP_CREATED
        );
    }

    public function show(FavouriteCollection $collection)
    {
        $this->authorizeCollection($collection);

        $collectionData = $this->showCollection->handle($collection);

        return ApiResponse::success(CollectionResource::make($collectionData));
    }

    public function update(UpdateCollectionRequest $request, FavouriteCollection $collection)
    {
        $this->authorizeCollection($collection);

        $collectionData = $this->updateCollection->handle(
            $collection,
            $request->toCollectionInput()
        );

        return ApiResponse::success(
            CollectionResource::make($collectionData),
            __('apiMessages.updated')
        );
    }

    public function destroy(FavouriteCollection $collection)
    {
        $this->authorizeCollection($collection);

        $this->deleteCollection->handle($collection);

        return ApiResponse::deleted();
    }

    private function authenticatedCompanyId(): ?int
    {
        return auth()->user()?->company_id;
    }

    private function authorizeCollection(FavouriteCollection $collection): void
    {
        $companyId = $this->authenticatedCompanyId();

        if ($companyId === null || $collection->company_id !== $companyId) {
            abort(403);
        }
    }
}

