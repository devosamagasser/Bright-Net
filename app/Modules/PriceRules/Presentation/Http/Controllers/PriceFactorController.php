<?php

namespace App\Modules\PriceRules\Presentation\Http\Controllers;

use App\Models\Supplier;
use Illuminate\Http\Request;
use App\Modules\Shared\Support\Helper\ApiResponse;
use App\Modules\PriceRules\Application\DTOs\PriceFactorInput;
use App\Modules\PriceRules\Application\UseCases\{
    ApplyPriceFactorUseCase,
    RevertPriceFactorUseCase,
    ReapplyPriceFactorUseCase,
    GetPriceFactorHistoryUseCase,
    GetProductsByPriceFactorUseCase
};
use App\Modules\PriceRules\Presentation\Http\Requests\ApplyPriceFactorRequest;
use App\Modules\PriceRules\Presentation\Resources\{
    PriceFactorResource,
    PriceFactorHistoryResource
};
use App\Modules\Products\Presentation\Resources\AllProductResource;

class PriceFactorController
{
    public function __construct(
        private readonly ApplyPriceFactorUseCase $applyFactor,
        private readonly RevertPriceFactorUseCase $revertFactor,
        private readonly ReapplyPriceFactorUseCase $reapplyFactor,
        private readonly GetPriceFactorHistoryUseCase $getHistory,
        private readonly GetProductsByPriceFactorUseCase $getProducts,
    ) {
    }

    public function apply(ApplyPriceFactorRequest $request)
    {
        $input = PriceFactorInput::fromArray($request->validated());
        $userId = $request->user()->id;

        $factor = $this->applyFactor->handle($request->supplier, $input, $userId);

        return ApiResponse::success(
            PriceFactorResource::make($factor),
            __('apiMessages.created'),
            \Illuminate\Http\Response::HTTP_CREATED
        );
    }

    public function revert(int $factorId)
    {
        $factor = $this->revertFactor->handle($factorId);

        return ApiResponse::success(
            PriceFactorResource::make($factor),
            __('apiMessages.updated')
        );
    }

    public function reapply(int $factorId)
    {
        $factor = $this->reapplyFactor->handle($factorId);

        return ApiResponse::success(
            PriceFactorResource::make($factor),
            __('apiMessages.created'),
            \Illuminate\Http\Response::HTTP_CREATED
        );
    }

    public function history(Request $request)
    {
        $perPage = $request->query('per_page', 15);
        $perPage = max(1, min(100, (int) $perPage));

        $history = $this->getHistory->handle($request->supplier, $perPage);

        if ($history instanceof \Illuminate\Contracts\Pagination\LengthAwarePaginator) {
            return ApiResponse::success(
                PriceFactorHistoryResource::collection($history)->resource
            );
        }

        return ApiResponse::success(
            PriceFactorHistoryResource::collection($history)
        );
    }

    public function products(int $factorId)
    {
        $products = $this->getProducts->handle($factorId);

        return ApiResponse::success(
            AllProductResource::collection($products)
        );
    }
}

