<?php

namespace App\Modules\PriceRules\Presentation\Http\Controllers;

use App\Models\Supplier;
use App\Modules\Products\Presentation\Resources\ProductResource;
use Illuminate\Http\Request;
use App\Modules\Shared\Support\Helper\ApiResponse;
use App\Modules\PriceRules\Application\DTOs\PriceFactorInput;
use App\Modules\PriceRules\Application\UseCases\{
    ApplyPriceFactorUseCase,
    GetPriceFactorHistoryUseCase,
    GetProductsByPriceFactorUseCase,
    FlattenPriceFactorsHistoryUseCase,
    RestorePriceFactorUseCase
};
use App\Modules\PriceRules\Application\DTOs\FlattenPriceFactorsHistoryInput;
use App\Modules\PriceRules\Presentation\Http\Requests\{
    ApplyPriceFactorRequest,
    FlattenPriceFactorsHistoryRequest
};
use App\Modules\PriceRules\Presentation\Resources\{
    PriceFactorResource,
    PriceFactorHistoryResource
};
use App\Modules\Products\Presentation\Resources\AllProductResource;

class PriceFactorController
{
    public function __construct(
        private readonly ApplyPriceFactorUseCase $applyFactor,
        private readonly GetPriceFactorHistoryUseCase $getHistory,
        private readonly GetProductsByPriceFactorUseCase $getProducts,
        private readonly FlattenPriceFactorsHistoryUseCase $flattenHistory,
        private readonly RestorePriceFactorUseCase $restoreFactor,
    ) {
    }

    public function apply(ApplyPriceFactorRequest $request)
    {
        $input = PriceFactorInput::fromArray($request->validated());
        $userId = $request->user()->id;

        $factor = $this->applyFactor->handle($request->supplier, $input, $userId);

        return ApiResponse::created(
            PriceFactorResource::make($factor),
        );
    }

    public function restore(int $factorId)
    {
        $factor = $this->restoreFactor->handle($factorId);

        return ApiResponse::updated(
            PriceFactorResource::make($factor),
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

    public function products(Request $request, int $factorId)
    {
        $perPage = (int) $request->query('per_page', 15);
        $filters = $request->all(['supplier_brand', 'supplier_department', 'subcategory', 'solution', 'family']);
        $currency = $request->query('currency', 'USD');
        $products = $this->getProducts->handle(
            $factorId,
            $request->supplier->id,
            $perPage,
            $filters,
            $currency
        );

        return ApiResponse::success(
            ProductResource::collection($products)->resource
        );
    }

    public function flatten(FlattenPriceFactorsHistoryRequest $request)
    {
        $input = FlattenPriceFactorsHistoryInput::fromArray($request->validated());
        $this->flattenHistory->handle($input, $request->supplier_id);

        return ApiResponse::message(
            'Flatten job queued successfully'
        );
    }
}

