<?php

namespace App\Modules\PriceRules\Application\UseCases;

use App\Models\Supplier;
use App\Modules\PriceRules\Application\DTOs\PriceFactorData;
use App\Modules\PriceRules\Application\DTOs\PriceFactorInput;
use App\Modules\PriceRules\Domain\Repositories\PriceRulesRepositoryInterface;

class ApplyPriceFactorUseCase
{
    public function __construct(
        private readonly PriceRulesRepositoryInterface $repository,
    ) {
    }

    public function handle(Supplier $supplier, PriceFactorInput $input, int $userId): PriceFactorData
    {
        $factor = $this->repository->applyPriceFactor(
            $supplier,
            $input->productIds,
            $input->factor,
            $userId,
            $input->notes
        );

        return PriceFactorData::fromModel($factor);
    }
}

