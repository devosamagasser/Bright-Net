<?php

namespace App\Modules\Shared\Presentation\Http\Controllers;

use App\Models\Supplier;
use App\Models\SupplierBrand;
use App\Models\SupplierDepartment;
use App\Models\SupplierSolution;
use App\Modules\Shared\Domain\Services\EasyAccessService;
use App\Modules\Shared\Presentation\Resources\EasyAccessResource;
use App\Modules\Shared\Support\Helper\ApiResponse;
use Illuminate\Http\Request;

class EasyAccessController
{
    public function __construct(
        private readonly EasyAccessService $easyAccess
    ) {
    }

    /**
     * Get list of currencies.
     */
    public function currencies()
    {
        $currencies = $this->easyAccess->listCurrencies();

        return ApiResponse::success(
            EasyAccessResource::collection($currencies)
        );
    }

    /**
     * Get list of solutions for the authenticated supplier.
     */
    public function solutions(Request $request)
    {
        $supplier = $this->getSupplier($request);
        $solutions = $this->easyAccess->listSolutions($supplier);

        return ApiResponse::success(
            EasyAccessResource::collection($solutions)
        );
    }

    /**
     * Get list of brands for a supplier solution.
     */
    public function brands(Request $request, SupplierSolution $supplierSolution)
    {
        $supplier = $this->getSupplier($request);
        $brands = $this->easyAccess->listBrands($supplier, $supplierSolution);

        return ApiResponse::success(
            EasyAccessResource::collection($brands)
        );
    }

    /**
     * Get list of departments (categories) for a supplier brand.
     */
    public function departments(Request $request, SupplierBrand $supplierBrand)
    {
        $supplier = $this->getSupplier($request);
        $departments = $this->easyAccess->listDepartments($supplier, $supplierBrand);

        return ApiResponse::success(
            EasyAccessResource::collection($departments)
        );
    }

    /**
     * Get list of subcategories for a supplier department.
     */
    public function subcategories(Request $request, SupplierDepartment $supplierDepartment)
    {
        $supplier = $this->getSupplier($request);
        $subcategories = $this->easyAccess->listSubcategories($supplier, $supplierDepartment);

        return ApiResponse::success(
            EasyAccessResource::collection($subcategories)
        );
    }

    /**
     * Get list of families for a subcategory and supplier department.
     */
    public function families(Request $request, SupplierDepartment $supplierDepartment, int $subcategory)
    {
        $supplier = $this->getSupplier($request);
        $families = $this->easyAccess->listFamilies(
            $subcategory,
            $supplierDepartment->id,
            $supplier->id
        );

        return ApiResponse::success(
            EasyAccessResource::collection($families)
        );
    }

    /**
     * Get list of unique origins from products.
     */
    public function origins(Request $request)
    {
        $supplier = $this->getSupplier($request);
        $origins = $this->easyAccess->listOrigins($supplier->id);

        return ApiResponse::success(
            EasyAccessResource::collection($origins)
        );
    }

    /**
     * Get the supplier from the request.
     */
    private function getSupplier(Request $request): Supplier
    {
        return $request->supplier;
    }
}

