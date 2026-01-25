<?php

namespace App\Modules\Products\Application\UseCases;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use App\Modules\Products\Application\DTOs\ProductData;
use App\Modules\Products\Domain\Repositories\ProductRepositoryInterface;

class ListProductsByGroupUseCase
{
    public function __construct(
        private readonly ProductRepositoryInterface $products,
    ) {
    }

    /**
     * @return array{products: LengthAwarePaginator, roots: array}
     */
    public function handle(int $groupId, int $perPage = 15, ?int $supplierId = null): array
    {
        $paginator = $this->products->paginateByGroup($groupId, $perPage, $supplierId);
        return [
            'products' => $paginator->setCollection(
                ProductData::collection($paginator->getCollection())
            ),
            'roots' => ProductData::serializeRoots(
                $paginator->getCollection()->first()
            ),
        ];
    }
}

