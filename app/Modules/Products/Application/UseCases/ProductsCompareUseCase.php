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

    public function handle(int $firstProduct, int $secondProduct)
    {
        $comparedProducts = $this->products->compare(
            $firstProduct,
            $secondProduct
        );

        return ProductComparedData::collection(
            $comparedProducts->first(),
            $comparedProducts->last()
        );
    }

}
