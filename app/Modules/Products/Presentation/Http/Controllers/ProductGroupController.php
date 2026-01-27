<?php

namespace App\Modules\Products\Presentation\Http\Controllers;

use Illuminate\Http\Request;
use App\Modules\Shared\Support\Helper\ApiResponse;
use App\Modules\Products\Presentation\Resources\ProductGroupResource;
use App\Modules\Products\Application\UseCases\{
    ListProductGroupsUseCase,
    ListProductsByGroupUseCase,
    CutPasteProductGroupUseCase
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
            ProductGroupResource::collection($paginator->items())
                ->additional([
                    'pagination' => [
                        'current_page' => $paginator->currentPage(),
                        'last_page' => $paginator->lastPage(),
                        'per_page' => $paginator->perPage(),
                        'total' => $paginator->total(),
                        'from' => $paginator->firstItem(),
                        'to' => $paginator->lastItem(),
                    ]
                ])
                ->resolve()
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

        $collection = ProductResource::collection($data['products']->items())
            ->additional([
                'roots' => $data['roots'],
                'pagination' => [
                    'current_page' => $data['products']->currentPage(),
                    'last_page' => $data['products']->lastPage(),
                    'per_page' => $data['products']->perPage(),
                    'total' => $data['products']->total(),
                    'from' => $data['products']->firstItem(),
                    'to' => $data['products']->lastItem(),
                ]
            ])
            ->response()
            ->getData(true);

        return ApiResponse::success($collection);
    }

}

