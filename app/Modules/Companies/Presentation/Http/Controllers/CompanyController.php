<?php

namespace App\Modules\Companies\Presentation\Http\Controllers;

use App\Modules\Companies\Domain\Models\Company;
use App\Modules\Companies\Application\UseCases\DeleteCompanyUseCase;

class CompanyController
{
    public function __construct(
        private readonly DeleteCompanyUseCase $deleteCompany,
    ) {
    }

    public function destroy(Company $company)
    {
        $this->deleteCompany->handle($company, $company->type);

        return response()->noContent();
    }
}
