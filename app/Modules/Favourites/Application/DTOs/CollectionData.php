<?php

namespace App\Modules\Favourites\Application\DTOs;

use App\Modules\Favourites\Domain\Models\FavouriteCollection;
use App\Modules\Products\Domain\Models\Product;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class CollectionData
{
    /**
     * @param array<int, array<string, mixed>> $products
     */
    private function __construct(
        public readonly int $id,
        public readonly string $name,
        public readonly int $companyId,
        public readonly string $companyName,
        public readonly ?LengthAwarePaginator $products = null,
        public readonly int $productsCount,
    ) {
    }

    public static function fromModel(FavouriteCollection $collection,?LengthAwarePaginator $products = null): self
    {
        return new self(
            id: $collection->getKey(),
            name: $collection->name,
            companyId: (int) $collection->company_id,
            companyName: $collection->company->name,
            products: $products,
            productsCount: $products ? $products->total() : $collection->products_count  ,
        );
    }

    /**
     * @return Collection<int, self>
     * @param Collection<int, Product> $products
     */
    public static function collection(Collection $products): Collection
    {
        return $products->map(static fn (FavouriteCollection $collection) => self::fromModel($collection));
    }

}

