<?php

namespace App\Modules\Favourites\Application\DTOs;

use App\Modules\Favourites\Domain\Models\FavouriteCollection;
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
        public readonly array $products,
        public readonly int $productsCount,
        public readonly string $createdAt,
        public readonly string $updatedAt,
    ) {
    }

    public static function fromModel(FavouriteCollection $collection, bool $withProducts = true): self
    {
        $products = [];
        $productsCount = 0;

        if ($collection->relationLoaded('products')) {
            $productsCount = $collection->products->count();

            if ($withProducts) {
                $products = $collection->products->map(static fn ($product) => [
                    'id' => $product->getKey(),
                    'code' => $product->code,
                    'name' => $product->name,
                ])->values()->all();
            }
        }

        return new self(
            id: $collection->getKey(),
            name: $collection->name,
            companyId: (int) $collection->company_id,
            products: $products,
            productsCount: $productsCount,
            createdAt: $collection->created_at?->toISOString() ?? '',
            updatedAt: $collection->updated_at?->toISOString() ?? '',
        );
    }

    /**
     * @param Collection<int, FavouriteCollection> $collections
     * @return Collection<int, self>
     */
    public static function collection(Collection $collections, bool $withProducts = true): Collection
    {
        return $collections->map(static fn (FavouriteCollection $collection) => self::fromModel($collection, $withProducts));
    }
}

