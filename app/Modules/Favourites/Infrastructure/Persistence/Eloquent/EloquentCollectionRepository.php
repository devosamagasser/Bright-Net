<?php

namespace App\Modules\Favourites\Infrastructure\Persistence\Eloquent;

use App\Models\Supplier;
use App\Modules\Brands\Domain\Models\Brand;
use App\Modules\Departments\Domain\Models\Department;
use App\Modules\Favourites\Domain\Models\FavouriteCollection;
use App\Modules\Favourites\Domain\Repositories\CollectionRepositoryInterface;
use App\Modules\Favourites\Domain\Services\RelationModelService;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class EloquentCollectionRepository implements CollectionRepositoryInterface
{
    public function paginateByCompany(int $companyId, int $perPage = 15): LengthAwarePaginator
    {
        return $this->query()
            ->with(['products'])
            ->where('company_id', $companyId)
            ->orderByDesc('id')
            ->paginate($perPage);
    }
    public function getByCompany(int $companyId, $perPage): LengthAwarePaginator
    {
        return FavouriteCollection::withCount('products')
            ->with('company')
            ->where('company_id', $companyId)
            ->paginate($perPage);
    }

    public function find(int $id, $perPage, $filter = []): array
    {
        $collection = FavouriteCollection::findOrFail($id);
        $collectionProducts = $collection
        ->products()
        ->filter($filter)
        ->with([
            'supplier',
            'solution',
            'department',
            'brand',
            'subcategory',
            'family',
            'fieldValues.field',
            'media',
        ])->paginate($perPage);
        return [
            'collection' => $collection,
            'products' => $collectionProducts
        ];
    }

    public function create(array $attributes): FavouriteCollection
    {
        $collection = FavouriteCollection::create($attributes);

        return $collection->load('products');
    }

    public function update(FavouriteCollection $collection, array $attributes): FavouriteCollection
    {
        $collection->fill($attributes);
        $collection->save();

        return $collection->load('products');
    }

    public function delete(FavouriteCollection $collection): void
    {
        $collection->delete();
    }

    public function addProduct(FavouriteCollection $collection, int $productId): void
    {
        $collection->products()->syncWithoutDetaching([$productId]);
    }

    public function removeProduct(FavouriteCollection $collection, int $productId): void
    {
        $collection->products()->detach($productId);
    }

    public function hasProduct(FavouriteCollection $collection, int $productId): bool
    {
        return $collection->products()->where('products.id', $productId)->exists();
    }

    protected function query(): Builder
    {
        return FavouriteCollection::query();
    }

    public function getRelatedModelsForCollection(int $collectionId, string $relationName, int $perPage = 15): LengthAwarePaginator
    {
        $collection = FavouriteCollection::findOrFail($collectionId);

        $relation = $collection->products()
            ->getModel()
            ->{$relationName}();

        $relationColumn = $relation->getForeignKeyName(); // brand_id مثلا
        $relationModel  = $relation->getRelated();        // Brand model object

        $ids = $collection->products()
            ->whereNotNull($relationColumn)
            ->distinct()
            ->pluck($relationColumn);

        return RelationModelService::modelQuery($relationModel, $ids, $perPage);
    }


}

