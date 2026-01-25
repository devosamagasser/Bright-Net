<?php

namespace App\Modules\Products\Application\UseCases;

use Illuminate\Support\Collection;
use App\Modules\Products\Application\DTOs\ProductData;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Modules\Products\Domain\Repositories\ProductRepositoryInterface;

class ShowProductUseCase
{
    public function __construct(private readonly ProductRepositoryInterface $products)
    {
    }

    public function handle(int $productId, ?string $currency = null)
    {
        $product = $this->products->find($productId);

        if ($product === null) {
            throw new ModelNotFoundException();
        }

        return ProductData::fromModel($product, true, $currency);

    }
}
