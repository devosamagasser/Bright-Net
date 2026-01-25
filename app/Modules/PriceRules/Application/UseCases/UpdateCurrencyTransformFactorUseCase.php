<?php

namespace App\Modules\PriceRules\Application\UseCases;

use App\Modules\PriceRules\Application\DTOs\CurrencyTransformFactorData;
use App\Modules\PriceRules\Application\DTOs\CurrencyTransformFactorInput;
use App\Modules\PriceRules\Domain\Models\CurrencyTransformFactor;
use App\Modules\PriceRules\Domain\Repositories\PriceRulesRepositoryInterface;

class UpdateCurrencyTransformFactorUseCase
{
    public function __construct(
        private readonly PriceRulesRepositoryInterface $repository,
    ) {
    }

    public function handle(CurrencyTransformFactor $factor, CurrencyTransformFactorInput $input): CurrencyTransformFactorData
    {
        $updatedFactor = $this->repository->updateCurrencyTransformFactor(
            $factor,
            $input->factor
        );

        return CurrencyTransformFactorData::fromModel($updatedFactor);
    }
}

