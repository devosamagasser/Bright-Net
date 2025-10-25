<?php

namespace App\Modules\SupplierEngagements\Presentation\Http\Controllers;

use App\Modules\Companies\Domain\Models\Company;
use App\Modules\Companies\Domain\ValueObjects\CompanyType;
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

    public function solutions(Company $company)
    {
        $company = $this->assertCompanyOwnership($company);
        $this->assertSupplierCompany($company);

        return ApiResponse::success(
            SupplierSolutionResource::collection($this->engagements->listSolutions($company))->resolve()
        );
    }

    public function brands(Company $company, SupplierSolution $supplierSolution)
    {
        $company = $this->assertCompanyOwnership($company);

        $this->assertSupplierCompany($company);

        return ApiResponse::success(
            SupplierBrandResource::collection($this->engagements->listBrands($company, $supplierSolution))->resolve()
        );
    }

    public function departments(Company $company, SupplierBrand $supplierBrand)
    {
        $company = $this->assertCompanyOwnership($company);
        $this->assertSupplierCompany($company);

        return ApiResponse::success(
            SupplierDepartmentResource::collection($this->engagements->listDepartments($company, $supplierBrand))->resolve()
        );
    }

    public function subcategories(Company $company, SupplierDepartment $supplierDepartment)
    {
        $company = $this->assertCompanyOwnership($company);
        $this->assertSupplierCompany($company);

        return ApiResponse::success(
            SupplierSubcategoryResource::collection($this->engagements->listSubcategories($company, $supplierDepartment))->resolve()
        );
    }

    private function assertSupplierCompany(Company $company): void
    {
        if ($company->type !== CompanyType::SUPPLIER) {
            abort(404);
        }
    }

    private function assertCompanyOwnership(Company $company)
    {

        return ($company->id === null) ?
            auth()->user()->company : $company;
    }
}
