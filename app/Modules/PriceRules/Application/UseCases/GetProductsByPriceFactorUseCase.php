<?php

namespace App\Modules\PriceRules\Application\UseCases;

use App\Modules\PriceRules\Domain\Repositories\PriceRulesRepositoryInterface;
use App\Modules\Products\Application\DTOs\ProductData;
use App\Modules\Products\Domain\Repositories\ProductRepositoryInterface;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class GetProductsByPriceFactorUseCase
{
    public function __construct(
        private readonly PriceRulesRepositoryInterface $repository,
        private readonly ProductRepositoryInterface $products,
    ) {
    }

    public function handle(int $factorId, int $supplierId, $perPage, $filter, $currency): LengthAwarePaginator
    {
        // Get the selected factor to get its created_at
        $selectedFactor = $this->repository->findPriceFactor($factorId);
        
        if ($selectedFactor === null) {
            // If factor not found, return empty paginator
            return new LengthAwarePaginator(collect(), 0, $perPage);
        }
        
        $maxFactorCreatedAt = $selectedFactor->created_at;
        
        // Get all products for the supplier (with filters)
        $paginator = $this->products->paginateAll($supplierId, $perPage, $filter, $currency);

        return $paginator->setCollection(
            ProductData::collection(
                $paginator->getCollection(),
                true,
                $currency,
                $maxFactorCreatedAt
            )
        );
    }
}

