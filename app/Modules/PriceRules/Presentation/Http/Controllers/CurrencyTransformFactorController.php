<?php

namespace App\Modules\PriceRules\Presentation\Http\Controllers;

use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Modules\Shared\Support\Helper\ApiResponse;
use App\Modules\PriceRules\Application\DTOs\CurrencyTransformFactorInput;
use App\Modules\PriceRules\Application\UseCases\{
    CreateCurrencyTransformFactorUseCase,
    UpdateCurrencyTransformFactorUseCase,
    ListCurrencyTransformFactorsUseCase
};
use App\Modules\PriceRules\Domain\Models\CurrencyTransformFactor;
use App\Modules\PriceRules\Domain\Repositories\PriceRulesRepositoryInterface;
use App\Modules\PriceRules\Presentation\Http\Requests\{
    StoreCurrencyTransformFactorRequest,
    UpdateCurrencyTransformFactorRequest
};
use App\Modules\PriceRules\Presentation\Resources\CurrencyTransformFactorResource;

class CurrencyTransformFactorController
{
    public function __construct(
        private readonly ListCurrencyTransformFactorsUseCase $listFactors,
        private readonly CreateCurrencyTransformFactorUseCase $createFactor,
        private readonly UpdateCurrencyTransformFactorUseCase $updateFactor,
        private readonly PriceRulesRepositoryInterface $repository,
    ) {
    }

    public function index(Request $request)
    {
        $factors = $this->listFactors->handle($request->supplier);

        return ApiResponse::success(
            CurrencyTransformFactorResource::collection($factors)
        );
    }

    public function store(StoreCurrencyTransformFactorRequest $request,)
    {
        $input = CurrencyTransformFactorInput::fromArray($request->validated());
        $factor = $this->createFactor->handle($request->supplier, $input);

        return ApiResponse::success(
            CurrencyTransformFactorResource::make($factor),
            __('apiMessages.created'),
            Response::HTTP_CREATED
        );
    }

    public function update(UpdateCurrencyTransformFactorRequest $request, CurrencyTransformFactor $currencyTransformFactor)
    {
        $input = CurrencyTransformFactorInput::fromArray([
            'from' => $currencyTransformFactor->from->value,
            'to' => $currencyTransformFactor->to->value,
            'factor' => $request->validated()['factor'],
        ]);

        $factor = $this->updateFactor->handle($currencyTransformFactor, $input);

        return ApiResponse::success(
            CurrencyTransformFactorResource::make($factor),
            __('apiMessages.updated')
        );
    }

    public function destroy(CurrencyTransformFactor $currencyTransformFactor)
    {
        $this->repository->deleteCurrencyTransformFactor($currencyTransformFactor);

        return ApiResponse::deleted();
    }
}

