<?php

namespace App\Modules\SupplierEngagements\Presentation\Http\Controllers;

use App\Models\Supplier;
use App\Modules\Shared\Support\Helper\ApiResponse;
use App\Modules\SupplierEngagements\Domain\Services\SupplierEngagementService;
use App\Modules\SupplierEngagements\Presentation\Resources\SupplierBrandResource;
use App\Modules\SupplierEngagements\Presentation\Resources\SupplierDepartmentResource;
use App\Modules\SupplierEngagements\Presentation\Resources\SupplierSolutionResource;
use App\Modules\SupplierEngagements\Presentation\Resources\SupplierSubcategoryResource;
use App\Models\SupplierBrand;
use App\Models\SupplierDepartment;
use App\Models\SupplierSolution;

class SupplierEngagementController
{
    public function __construct(private readonly SupplierEngagementService $engagements)
    {
    }

    public function solutions(?Supplier $supplier = null)
    {
        $supplier = $this->assertSupplier($supplier);
        $solutions = $this->engagements->listSolutions($supplier);
        return ApiResponse::success(
            SupplierSolutionResource::collection($solutions)
        );
    }

    public function brands(SupplierSolution $supplierSolution, ?Supplier $supplier = null )
    {
        $supplier = $this->assertSupplier($supplier);
        $resource = $this->engagements->listBrands($supplier, $supplierSolution);
        return ApiResponse::success(SupplierBrandResource::collection($resource));
    }

    public function departments(SupplierBrand $supplierBrand, ?Supplier $supplier = null)
    {
        $supplier = $this->assertSupplier($supplier);

        return ApiResponse::success(
            SupplierDepartmentResource::collection($this->engagements->listDepartments($supplier, $supplierBrand))->resolve()
        );
    }

    public function subcategories(SupplierDepartment $supplierDepartment, ?Supplier $supplier = null)
    {
        $supplier = $this->assertSupplier($supplier);
        $subcategories = $this->engagements->listSubcategories($supplier, $supplierDepartment);

        $resource = SupplierSubcategoryResource::collection($subcategories)
            ->additional(['department' => [
                'id' => $supplierDepartment->id,
                'name' => $supplierDepartment->department->name,
            ],
        ])
        ->response()
        ->getData(true);

        return ApiResponse::success($resource);

    }

    private function assertSupplier($supplier = null)
    {
        return $supplier ?? request()->supplier;
    }
}
