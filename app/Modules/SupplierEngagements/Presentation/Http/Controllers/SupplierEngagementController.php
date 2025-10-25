<?php

namespace App\Modules\SupplierEngagements\Presentation\Http\Controllers;

use App\Models\SupplierBrand;
use App\Models\SupplierDepartment;
use App\Models\SupplierSolution;
use App\Modules\Companies\Domain\Models\Company;
use App\Modules\Companies\Domain\ValueObjects\CompanyType;
use App\Modules\Shared\Support\Helper\ApiResponse;
use App\Modules\SupplierEngagements\Domain\Services\SupplierEngagementService;

class SupplierEngagementController
{
    public function __construct(private readonly SupplierEngagementService $engagements)
    {
    }

    public function solutions(Company $company)
    {
        $company = $this->assertCompanyOwnership($company);
        $this->assertSupplierCompany($company);

        return ApiResponse::success($this->engagements->listSolutions($company));
    }

    public function brands(Company $company, SupplierSolution $supplierSolution)
    {
        $company = $this->assertCompanyOwnership($company);

        $this->assertSupplierCompany($company);

        return ApiResponse::success($this->engagements->listBrands($company, $supplierSolution));
    }

    public function departments(Company $company, SupplierBrand $supplierBrand)
    {
        $company = $this->assertCompanyOwnership($company);
        $this->assertSupplierCompany($company);

        return ApiResponse::success($this->engagements->listDepartments($company, $supplierBrand));
    }

    public function subcategories(Company $company, SupplierDepartment $supplierDepartment)
    {
        $company = $this->assertCompanyOwnership($company);
        $this->assertSupplierCompany($company);

        return ApiResponse::success($this->engagements->listSubcategories($company, $supplierDepartment));
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
