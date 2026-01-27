<?php

namespace App\Modules\PriceRules\Application\UseCases;

use App\Models\Supplier;
use App\Modules\PriceRules\Application\DTOs\PriceFactorData;
use App\Modules\PriceRules\Application\DTOs\PriceFactorInput;
use App\Modules\PriceRules\Domain\Jobs\ApplyProductPricesFactor;
use App\Modules\PriceRules\Domain\Jobs\FlattenPriceFactorsHistoryJob;
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
            $supplier->id,
            $input->factor,
            $userId,
            $input->notes,
        );

        ApplyProductPricesFactor::dispatch(
            $supplier->id,
            $factor->id,
            $input->productIds,
            $input->brandId,
            $input->categoryId,
            $input->subcategoryId,
            $input->familyId
        );

        return PriceFactorData::fromModel($factor);
    }
}

