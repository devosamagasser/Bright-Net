<?php

namespace App\Modules\PriceRules\Application\UseCases;

use App\Models\Supplier;
use App\Modules\PriceRules\Application\DTOs\PriceFactorHistoryData;
use App\Modules\PriceRules\Domain\Repositories\PriceRulesRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class GetPriceFactorHistoryUseCase
{
    public function __construct(
        private readonly PriceRulesRepositoryInterface $repository,
    ) {
    }

    /**
     * @return LengthAwarePaginator|Collection<int, PriceFactorHistoryData>
     */
    public function handle(Supplier $supplier, ?int $perPage = null): LengthAwarePaginator|Collection
    {
        $history = $this->repository->getPriceFactorHistory($supplier, $perPage);

        if ($history instanceof LengthAwarePaginator) {
            return $history->setCollection(
                $history->getCollection()->map(function ($factor) {
                    $products = $this->repository->getProductsByPriceFactor($factor->id);
                    return PriceFactorHistoryData::fromModel($factor, $products);
                })
            );
        }

        return $history->map(function ($factor) {
            $products = $this->repository->getProductsByPriceFactor($factor->id);
            return PriceFactorHistoryData::fromModel($factor, $products);
        });
    }
}

