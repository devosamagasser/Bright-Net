<?php

namespace App\Modules\Products\Application\UseCases;

use App\Modules\Products\Domain\Models\Product;
use App\Modules\Products\Domain\Repositories\ProductRepositoryInterface;

class DeleteProductUseCase
{
    public function __construct(private readonly ProductRepositoryInterface $products)
    {
    }

    public function handle(Product $product): void
    {
        $this->products->delete($product);
    }
}
