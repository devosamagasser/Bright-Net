<?php

namespace App\Modules\Products\Presentation\Http\Controllers;

use Illuminate\Http\Request;
use App\Modules\Shared\Support\Helper\ApiResponse;
use App\Modules\Products\Presentation\Resources\ProductGroupResource;
use App\Modules\Products\Application\UseCases\{
    ListProductGroupsUseCase,
    ListProductsByGroupUseCase,
};
use App\Modules\Products\Domain\Models\ProductGroup;
use App\Modules\Products\Presentation\Resources\ProductResource;
use App\Modules\Products\Application\DTOs\ProductData;

class ProductGroupController
{
    public function __construct(
        private readonly ListProductGroupsUseCase $listGroups,
        private readonly ListProductsByGroupUseCase $listProductsByGroup,
    ) {
    }

    public function index(Request $request, int $family)
    {
        $perPage = (int) $request->query('per_page', 15);

        $paginator = $this->listGroups->handle(
            $family,
            $perPage,
            $request->supplier_id
        );

        return ApiResponse::success(
            ProductGroupResource::collection($paginator)
                ->response()
                ->getData(true)
        );
    }

    public function products(Request $request, ProductGroup $group)
    {
        $perPage = (int) $request->query('per_page', 15);
        $data = $this->listProductsByGroup->handle(
            $group->id,
            $perPage,
            $request->supplier_id
        );

        $collection = ProductResource::collection($data['products'])
            ->additional([
                'roots' => $data['roots']
            ])
            ->response()
            ->getData(true);

        return ApiResponse::success($collection);
    }

}

