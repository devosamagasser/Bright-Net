<?php

namespace App\Modules\Favourites\Infrastructure\Persistence\Eloquent;

use App\Modules\Favourites\Domain\Models\FavouriteCollection;
use App\Modules\Favourites\Domain\Repositories\CollectionRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;

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
    public function getByCompany(int $companyId): Collection
    {
        return $this->query()
            ->with(['products'])
            ->where('company_id', $companyId)
            ->orderByDesc('id')
            ->get();
    }

    public function find(int $id): ?FavouriteCollection
    {
        return $this->query()
            ->with(['products'])
            ->find($id);
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
}

