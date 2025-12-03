<?php

namespace App\Modules\Products\Application\UseCases;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Modules\Products\Application\DTOs\ProductData;
use App\Modules\Products\Domain\Repositories\ProductRepositoryInterface;

class ShowProductUseCase
{
    public function __construct(private readonly ProductRepositoryInterface $products)
    {
    }

    public function handle(int $productId)
    {
        $product = $this->products->find($productId);

        if ($product === null) {
            throw new ModelNotFoundException();
        }

        return ProductData::fromModel($product);
    }
}
