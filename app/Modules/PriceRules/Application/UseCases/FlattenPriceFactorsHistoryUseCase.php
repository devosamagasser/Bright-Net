<?php

namespace App\Modules\PriceRules\Application\UseCases;

use App\Modules\PriceRules\Application\DTOs\FlattenPriceFactorsHistoryInput;
use App\Modules\PriceRules\Domain\Jobs\FlattenPriceFactorsHistoryJob;
use App\Modules\PriceRules\Domain\Repositories\PriceRulesRepositoryInterface;
use Illuminate\Support\Str;

class FlattenPriceFactorsHistoryUseCase
{
    public function __construct(
        private readonly PriceRulesRepositoryInterface $repository,
    ) {
    }

    public function handle(FlattenPriceFactorsHistoryInput $input, int $supplierId)
    {
        $factorsIdsToFlatten = $this->repository->getFactorsToFlatten($supplierId, $input->factorId);
        FlattenPriceFactorsHistoryJob::dispatch(
            $factorsIdsToFlatten,
            $this->repository
        );
    }

}

