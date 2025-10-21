<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Modules\Brands\Domain\Models\Brand;
use App\Modules\Geography\Domain\Models\Region;
use App\Modules\Departments\Domain\Models\Department;
use App\Modules\SolutionsCatalog\Domain\Models\Solution;

class SolutionBrandEndpointsTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_lists_brands_for_a_solution(): void
    {
        $solution = $this->createSolution('Unified Communications');
        $region = Region::create(['name' => 'MENA']);

        $brand = Brand::create([
            'name' => 'Cisco',
            'region_id' => $region->getKey(),
        ]);

        $otherBrand = Brand::create([
            'name' => 'Microsoft',
            'region_id' => $region->getKey(),
        ]);

        $departmentA = $this->createDepartment($solution, 'Networking');
        $departmentB = $this->createDepartment($solution, 'Security');

        $solution->brands()->attach($brand->getKey());
        $solution->brands()->attach($otherBrand->getKey());

        $brand->departments()->attach([$departmentA->getKey(), $departmentB->getKey()]);
        $otherBrand->departments()->attach([$departmentA->getKey()]);

        $response = $this->getJson(route('api.solutions.brands.index', ['solution' => $solution->getKey()]));

        $response->assertOk();
        $response->assertJsonFragment(['id' => $brand->getKey(), 'name' => 'Cisco']);
        $response->assertJsonFragment(['id' => $departmentA->getKey(), 'name' => 'Networking']);
        $response->assertJsonFragment(['id' => $departmentB->getKey(), 'name' => 'Security']);
    }

    public function test_it_shows_a_brand_within_a_solution(): void
    {
        $solution = $this->createSolution('Cyber Security');
        $region = Region::create(['name' => 'Europe']);

        $brand = Brand::create([
            'name' => 'Fortinet',
            'region_id' => $region->getKey(),
        ]);

        $department = $this->createDepartment($solution, 'Threat Detection');

        $solution->brands()->attach($brand->getKey());
        $brand->departments()->attach($department->getKey());

        $response = $this->getJson(route('api.solutions.brands.show', [
            'solution' => $solution->getKey(),
            'brand' => $brand->getKey(),
        ]));

        $response->assertOk();
        $response->assertJsonPath('data.id', $brand->getKey());
        $response->assertJsonPath('data.departments.0.id', $department->getKey());
        $response->assertJsonPath('data.departments.0.name', 'Threat Detection');
    }

    public function test_it_returns_not_found_for_unrelated_brand(): void
    {
        $solution = $this->createSolution('Cloud');
        $otherSolution = $this->createSolution('Data Center');
        $region = Region::create(['name' => 'APAC']);

        $brand = Brand::create([
            'name' => 'AWS',
            'region_id' => $region->getKey(),
        ]);

        $department = $this->createDepartment($otherSolution, 'Storage');

        $otherSolution->brands()->attach($brand->getKey());
        $brand->departments()->attach($department->getKey());

        $response = $this->getJson(route('api.solutions.brands.show', [
            'solution' => $solution->getKey(),
            'brand' => $brand->getKey(),
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
}
