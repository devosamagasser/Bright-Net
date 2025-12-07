<?php

namespace App\Modules\Products\Application\UseCases;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use App\Modules\Families\Domain\Models\Family;
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
        $family = Family::find($familyId);
        return ProductData::collection($products, $family ?? null);
    }

}
