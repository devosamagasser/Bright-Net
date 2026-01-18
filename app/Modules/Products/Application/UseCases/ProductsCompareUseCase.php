<?php

namespace App\Modules\Products\Application\UseCases;

use App\Modules\Products\Domain\Models\Product;
use App\Modules\Products\Domain\Services\ProductPriceService;
use App\Modules\Products\Application\DTOs\ProductComparedData;
use App\Modules\Products\Domain\Repositories\ProductRepositoryInterface;


class ProductsCompareUseCase
{
    public function __construct(
        private readonly ProductRepositoryInterface $products,
    ) {
    }

    /**
     * @param  array<int>  $productIds
     */
    public function handle(array $productIds)
    {
        $comparedProducts = $this->products->compare($productIds);

        return ProductComparedData::collection($comparedProducts);
    }

}
