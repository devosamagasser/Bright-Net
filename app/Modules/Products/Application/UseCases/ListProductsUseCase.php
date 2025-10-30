<?php

namespace App\Modules\Products\Application\UseCases;

use Illuminate\Support\Collection;
use App\Modules\Products\Application\DTOs\ProductData;
use App\Modules\Products\Domain\Repositories\ProductRepositoryInterface;

class ListProductsUseCase
{
    public function __construct(private readonly ProductRepositoryInterface $products)
    {
    }

    /**
     * @return Collection<int, ProductData>
     */
    public function handle(int $familyId, ?int $supplierId = null): Collection
    {
        $products = $this->products->getByFamily($familyId, $supplierId);

        return ProductData::collection($products);
    }
}
