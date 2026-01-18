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
        private readonly CutPasteProductGroupUseCase $cutPasteGroup,
    ) {
    }

    public function index(Request $request, int $family)
    {
        $supplierId = $this->authenticatedSupplierId() ?? $request->query('supplier_id');
        $perPage = (int) $request->query('per_page', 15);
        
        $paginator = $this->listGroups->handle(
            $family, 
            $perPage,
            $supplierId !== null ? (int) $supplierId : null
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

    public function products(Request $request, int $group)
    {
        $supplierId = $this->authenticatedSupplierId() ?? $request->query('supplier_id');
        $perPage = (int) $request->query('per_page', 15);
        
        $data = $this->listProductsByGroup->handle(
            $group, 
            $perPage,
            $supplierId !== null ? (int) $supplierId : null
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

    public function paste(Request $request, ProductGroup $group)
    {
        $request->validate([
            'family_id' => ['required', 'integer', 'exists:families,id'],
        ]);

        $destinationFamilyId = $request->input('family_id');
        $updatedGroup = $this->cutPasteGroup->handle($group, (int) $destinationFamilyId);

        $groupData = \App\Modules\Products\Application\DTOs\ProductGroupData::fromModel($updatedGroup);
        
        return ApiResponse::updated(
            ProductGroupResource::make($groupData)->resolve()
        );
    }

    private function authenticatedSupplierId(): ?int
    {
        return auth()->user()?->company?->supplier?->id;
    }
}

