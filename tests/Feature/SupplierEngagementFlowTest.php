<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Supplier;
use App\Models\SupplierBrand;
use App\Models\SupplierDepartment;
use App\Models\SupplierSolution;
use App\Modules\Brands\Domain\Models\Brand;
use App\Modules\Companies\Domain\Models\Company;
use App\Modules\Companies\Domain\ValueObjects\CompanyType;
use App\Modules\Departments\Domain\Models\Department;
use App\Modules\Geography\Domain\Models\Region;
use App\Modules\SolutionsCatalog\Domain\Models\Solution;
use App\Modules\Subcategories\Domain\Models\Subcategory;

class SupplierEngagementFlowTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_returns_the_full_supplier_engagement_flow(): void
    {
        $company = Company::create([
            'name' => 'Bright Distributor',
            'description' => null,
            'type' => CompanyType::SUPPLIER,
        ]);

        $supplier = Supplier::create([
            'company_id' => $company->getKey(),
            'contact_email' => 'contact@example.com',
            'contact_phone' => '123456789',
            'website' => 'https://example.com',
        ]);

        $solution = $this->createSolution('Cloud Services');
        $region = Region::create(['name' => 'EMEA']);
        $brand = Brand::create([
            'name' => 'Azure',
            'region_id' => $region->getKey(),
        ]);
        $department = $this->createDepartment($solution, 'Infrastructure');
        $subcategory = $this->createSubcategory($department, 'Networking');

        $supplierSolution = SupplierSolution::create([
            'supplier_id' => $supplier->getKey(),
            'solution_id' => $solution->getKey(),
        ]);

        $supplierBrand = SupplierBrand::create([
            'supplier_solution_id' => $supplierSolution->getKey(),
            'brand_id' => $brand->getKey(),
        ]);

        $supplierDepartment = SupplierDepartment::create([
            'supplier_brand_id' => $supplierBrand->getKey(),
            'department_id' => $department->getKey(),
        ]);

        $solutionsResponse = $this->getJson(route('api.companies.suppliers.engagements.solutions.index', [
            'company' => $company->getKey(),
        ]));

        $solutionsResponse->assertOk();
        $solutionsResponse->assertJsonPath('data.0.supplier_solution_id', $supplierSolution->getKey());
        $solutionsResponse->assertJsonPath('data.0.solution.id', $solution->getKey());
        $solutionsResponse->assertJsonPath('data.0.solution.name', 'Cloud Services');

        $brandsResponse = $this->getJson(route('api.companies.suppliers.engagements.solutions.brands.index', [
            'company' => $company->getKey(),
            'supplierSolution' => $supplierSolution->getKey(),
        ]));

        $brandsResponse->assertOk();
        $brandsResponse->assertJsonPath('data.0.supplier_brand_id', $supplierBrand->getKey());
        $brandsResponse->assertJsonPath('data.0.id', $brand->getKey());
        $brandsResponse->assertJsonPath('data.0.name', 'Azure');

        $departmentsResponse = $this->getJson(route('api.companies.suppliers.engagements.brands.departments.index', [
            'company' => $company->getKey(),
            'supplierBrand' => $supplierBrand->getKey(),
        ]));

        $departmentsResponse->assertOk();
        $departmentsResponse->assertJsonPath('data.0.supplier_department_id', $supplierDepartment->getKey());
        $departmentsResponse->assertJsonPath('data.0.id', $department->getKey());
        $departmentsResponse->assertJsonPath('data.0.name', 'Infrastructure');
        $departmentsResponse->assertJsonPath('data.0.cover', null);

        $subcategoriesResponse = $this->getJson(route('api.companies.suppliers.engagements.departments.subcategories.index', [
            'company' => $company->getKey(),
            'supplierDepartment' => $supplierDepartment->getKey(),
        ]));

        $subcategoriesResponse->assertOk();
        $subcategoriesResponse->assertJsonPath('data.0.id', $subcategory->getKey());
        $subcategoriesResponse->assertJsonPath('data.0.name', 'Networking');
    }

    public function test_it_rejects_non_supplier_companies(): void
    {
        $company = Company::create([
            'name' => 'Consultants Inc.',
            'description' => null,
            'type' => CompanyType::CONSULTANT,
        ]);

        $response = $this->getJson(route('api.companies.suppliers.engagements.solutions.index', [
            'company' => $company->getKey(),
        ]));

        $response->assertNotFound();
    }

    public function test_it_prevents_access_to_foreign_brands(): void
    {
        $primaryCompany = Company::create([
            'name' => 'Primary Supplier',
            'description' => null,
            'type' => CompanyType::SUPPLIER,
        ]);

        $otherCompany = Company::create([
            'name' => 'Other Supplier',
            'description' => null,
            'type' => CompanyType::SUPPLIER,
        ]);

        $primarySupplier = Supplier::create(['company_id' => $primaryCompany->getKey()]);
        $otherSupplier = Supplier::create(['company_id' => $otherCompany->getKey()]);

        $solution = $this->createSolution('Security');
        $region = Region::create(['name' => 'GCC']);
        $brand = Brand::create([
            'name' => 'SecureCorp',
            'region_id' => $region->getKey(),
        ]);

        $foreignSolution = SupplierSolution::create([
            'supplier_id' => $otherSupplier->getKey(),
            'solution_id' => $solution->getKey(),
        ]);

        $foreignBrand = SupplierBrand::create([
            'supplier_solution_id' => $foreignSolution->getKey(),
            'brand_id' => $brand->getKey(),
        ]);

        $response = $this->getJson(route('api.companies.suppliers.engagements.solutions.brands.index', [
            'company' => $primaryCompany->getKey(),
            'supplierSolution' => $foreignSolution->getKey(),
        ]));

        $response->assertNotFound();

        $response = $this->getJson(route('api.companies.suppliers.engagements.brands.departments.index', [
            'company' => $primaryCompany->getKey(),
            'supplierBrand' => $foreignBrand->getKey(),
        ]));

        $response->assertNotFound();
    }

    private function createSolution(string $name): Solution
    {
        $solution = new Solution();
        $solution->save();

        $solution->translations()->create([
            'name' => $name,
            'locale' => 'en',
        ]);

        return $solution->refresh();
    }

    private function createDepartment(Solution $solution, string $name): Department
    {
        $department = new Department([
            'solution_id' => $solution->getKey(),
        ]);
        $department->save();

        $department->translations()->create([
            'name' => $name,
            'locale' => 'en',
        ]);

        return $department->refresh();
    }

    private function createSubcategory(Department $department, string $name): Subcategory
    {
        $subcategory = new Subcategory([
            'department_id' => $department->getKey(),
        ]);
        $subcategory->save();

        $subcategory->translations()->create([
            'name' => $name,
            'locale' => 'en',
        ]);

        return $subcategory->refresh();
    }
}
