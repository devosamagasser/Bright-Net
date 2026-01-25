<?php

namespace App\Modules\PriceRules\Application\UseCases;

use App\Models\Supplier;
use App\Modules\PriceRules\Application\DTOs\CurrencyTransformFactorData;
use App\Modules\PriceRules\Application\DTOs\CurrencyTransformFactorInput;
use App\Modules\PriceRules\Domain\Repositories\PriceRulesRepositoryInterface;

class CreateCurrencyTransformFactorUseCase
{
    public function __construct(
        private readonly PriceRulesRepositoryInterface $repository,
    ) {
    }

    public function handle(Supplier $supplier, CurrencyTransformFactorInput $input): CurrencyTransformFactorData
    {
        $factor = $this->repository->storeCurrencyTransformFactor(
            $supplier,
            $input->from,
            $input->to,
            $input->factor
        );

        return CurrencyTransformFactorData::fromModel($factor);
    }
}

