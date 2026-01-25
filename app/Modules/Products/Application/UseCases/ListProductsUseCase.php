<?php

namespace App\Modules\Products\Application\UseCases;

use App\Modules\Families\Domain\Repositories\FamilyRepositoryInterface;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use App\Modules\Families\Domain\Models\Family;
use App\Modules\Products\Application\DTOs\ProductData;
use App\Modules\Products\Domain\Repositories\ProductRepositoryInterface;

class ListProductsUseCase
{
    public function __construct(
        private readonly ProductRepositoryInterface $products,
    )
    {
    }

    /**
     * @return array{products: LengthAwarePaginator, roots: array}
     */
    public function handle(int $supplierId, int $perPage = 15, array $filters = [], ?string $currency = null) : LengthAwarePaginator
    {
        $paginator = $this->products->paginateAll(
            $supplierId,
            $perPage,
            $filters,
            $currency ?? 'USD'
        );

        return $paginator->setCollection(
            ProductData::collection($paginator->getCollection(), true, $currency)
        );
    }

}
