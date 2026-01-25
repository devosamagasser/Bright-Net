<?php

namespace App\Modules\PriceRules\Application\UseCases;

use App\Modules\PriceRules\Domain\Repositories\PriceRulesRepositoryInterface;
use Illuminate\Support\Collection;

class GetProductsByPriceFactorUseCase
{
    public function __construct(
        private readonly PriceRulesRepositoryInterface $repository,
    ) {
    }

    public function handle(int $factorId): Collection
    {
        return $this->repository->getProductsByPriceFactor($factorId);
    }
}

