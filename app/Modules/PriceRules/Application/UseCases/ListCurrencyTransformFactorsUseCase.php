<?php

namespace App\Modules\PriceRules\Application\UseCases;

use App\Models\Supplier;
use App\Modules\PriceRules\Application\DTOs\CurrencyTransformFactorData;
use App\Modules\PriceRules\Domain\Repositories\PriceRulesRepositoryInterface;
use Illuminate\Support\Collection;

class ListCurrencyTransformFactorsUseCase
{
    public function __construct(
        private readonly PriceRulesRepositoryInterface $repository,
    ) {
    }

    /**
     * @return Collection<int, CurrencyTransformFactorData>
     */
    public function handle(Supplier $supplier): Collection
    {
        $factors = $this->repository->getCurrencyTransformFactors($supplier);

        return $factors->map(fn ($factor) => CurrencyTransformFactorData::fromModel($factor));
    }
}

