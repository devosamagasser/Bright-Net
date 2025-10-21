<?php

namespace App\Modules\Companies\Presentation\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Modules\Companies\Application\DTOs\CompanyInput;
use App\Modules\Shared\Support\Helper\ApiResponse;
use App\Modules\Companies\Presentation\Resources\CompanyResource;
use App\Modules\Companies\Presentation\Http\Requests\{StoreCompanyRequest, UpdateCompanyRequest};
use App\Modules\Companies\Application\UseCases\{CreateCompanyUseCase, DeleteCompanyUseCase, ListCompaniesUseCase, ShowCompanyUseCase, UpdateCompanyUseCase};

class CompanyController
{
    public function __construct(
        private readonly ListCompaniesUseCase $listCompanies,
        private readonly ShowCompanyUseCase $showCompany,
        private readonly CreateCompanyUseCase $createCompany,
        private readonly UpdateCompanyUseCase $updateCompany,
        private readonly DeleteCompanyUseCase $deleteCompany,
    ) {
    }

    public function index(Request $request)
    {
        $perPage = (int) $request->query('per_page', 15);
        $perPage = max(1, min(100, $perPage));
        $filter = $request->all(['type', 'name']);

        $paginator = $this->listCompanies->handle($perPage, $filter);

        return ApiResponse::success(
            CompanyResource::collection($paginator)->resource
        );
    }

    public function store(StoreCompanyRequest $request)
    {
        $input = CompanyInput::fromArray($request->validated());
        $company = $this->createCompany->handle($input);

        return ApiResponse::success(
            CompanyResource::make($company),
            __('apiMessages.created'),
            Response::HTTP_CREATED
        );
    }

    public function show(int $company)
    {
        $companyData = $this->showCompany->handle($company);

        return ApiResponse::success(CompanyResource::make($companyData));
    }

    public function update(UpdateCompanyRequest $request, int $company)
    {
        $input = CompanyInput::fromArray($request->validated());
        $companyData = $this->updateCompany->handle($company, $input);

        return ApiResponse::success(
            CompanyResource::make($companyData),
            __('apiMessages.updated')
        );
    }

    public function destroy(int $company)
    {
        $this->deleteCompany->handle($company);

        return ApiResponse::deleted();
    }
}
