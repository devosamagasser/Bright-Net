<?php

namespace App\Modules\Companies\Presentation\Http\Controllers;

use App\Modules\Companies\Domain\Models\Company;
use App\Modules\Shared\Support\Helper\ApiResponse;
use App\Modules\Companies\Domain\ValueObjects\CompanyType;
use App\Modules\Companies\Presentation\Resources\CompanyResource;
use App\Modules\Companies\Application\UseCases\ShowCompanyUseCase;
use App\Modules\Companies\Application\UseCases\CreateCompanyUseCase;
use App\Modules\Companies\Application\UseCases\DeleteCompanyUseCase;
use App\Modules\Companies\Application\UseCases\UpdateCompanyUseCase;
use App\Modules\Companies\Application\UseCases\ListCompaniesByTypeUseCase;
use App\Modules\Companies\Presentation\Http\Requests\Suppliers\StoreSupplierRequest;
use App\Modules\Companies\Presentation\Http\Requests\Suppliers\UpdateSupplierRequest;

class SupplierCompanyController
{
    public function __construct(
        private readonly ListCompaniesByTypeUseCase $listCompanies,
        private readonly ShowCompanyUseCase $showCompany,
        private readonly CreateCompanyUseCase $createCompany,
        private readonly UpdateCompanyUseCase $updateCompany,
        private readonly DeleteCompanyUseCase $deleteCompany,
    ) {
    }

    public function index()
    {
        $paginator = $this->listCompanies->handle(CompanyType::SUPPLIER, (int) request()->query('per_page', 15));

        return ApiResponse::success(CompanyResource::collection($paginator));
    }

    public function store(StoreSupplierRequest $request): CompanyResource
    {
        $company = $this->createCompany->handle($request->toCompanyInput());

        return new CompanyResource($company);
    }

    public function show(Company $company): CompanyResource
    {
        if ($company->type !== CompanyType::SUPPLIER) {
            abort(404);
        }

        $companyData = $this->showCompany->handle($company, CompanyType::SUPPLIER);

        return new CompanyResource($companyData);
    }

    public function update(UpdateSupplierRequest $request, Company $company): CompanyResource
    {
        if ($company->type !== CompanyType::SUPPLIER) {
            abort(404);
        }

        $companyData = $this->updateCompany->handle($company, $request->toCompanyInput($company));

        return new CompanyResource($companyData);
    }

    public function destroy(Company $company)
    {
        if ($company->type !== CompanyType::SUPPLIER) {
            abort(404);
        }

        $this->deleteCompany->handle($company, CompanyType::SUPPLIER);

        return response()->noContent();
    }
}
