<?php

namespace App\Modules\PriceRules\Application\UseCases;

use App\Modules\PriceRules\Application\DTOs\PriceFactorData;
use App\Modules\PriceRules\Domain\Repositories\PriceRulesRepositoryInterface;

class RevertPriceFactorUseCase
{
    public function __construct(
        private readonly PriceRulesRepositoryInterface $repository,
    ) {
    }

    public function handle(int $factorId): PriceFactorData
    {
        $factor = $this->repository->revertPriceFactor($factorId);

        return PriceFactorData::fromModel($factor);
    }
}

