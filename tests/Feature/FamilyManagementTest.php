<?php

namespace Tests\Feature;

use App\Models\CompanyUser;
use App\Models\Supplier;
use App\Modules\Companies\Domain\Models\Company;
use App\Modules\Companies\Domain\ValueObjects\CompanyType;
use App\Modules\DataSheets\Domain\Models\{DataField, DataTemplate};
use App\Modules\DataSheets\Domain\ValueObjects\DataFieldType;
use App\Modules\DataSheets\Domain\ValueObjects\DataTemplateType;
use App\Modules\Departments\Domain\Models\Department;
use App\Modules\SolutionsCatalog\Domain\Models\Solution;
use App\Modules\Subcategories\Domain\Models\Subcategory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Tests\TestCase;

class FamilyManagementTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        config(['auth.defaults.guard' => 'company']);
        Auth::shouldUse('company');
    }

    public function test_supplier_can_create_family_with_dynamic_values(): void
    {
        $subcategory = $this->createSubcategory();
        $template = $this->createTemplateWithFields($subcategory);
        $supplier = $this->createSupplier();
        $this->authenticateSupplier($supplier);

        $payload = [
            'subcategory_id' => $subcategory->getKey(),
            'supplier_id' => $supplier->getKey(),
            'data_template_id' => $template->getKey(),
            'translations' => [
                'en' => [
                    'name' => 'Lighting Family',
                    'description' => 'Details for lighting family',
                ],
            ],
            'values' => [
                'model_number' => 'MN-100',
                'lighting_template_option' => 'One',
            ],
        ];

        $response = $this->postJson('/api/families', $payload);

        $response->assertStatus(Response::HTTP_CREATED);
        $response->assertJsonPath('data.name', 'Lighting Family');
        $response->assertJsonPath('data.values.0.field.slug', 'model_number');
        $response->assertJsonPath('data.values.1.value', 'One');

        $this->assertDatabaseHas('families', [
            'subcategory_id' => $subcategory->getKey(),
            'supplier_id' => $supplier->getKey(),
            'data_template_id' => $template->getKey(),
        ]);

        $modelField = $template->fields->firstWhere('slug', 'model_number');
        $this->assertDatabaseHas('family_field_values', [
            'data_field_id' => $modelField?->getKey(),
        ]);
    }

    public function test_family_creation_requires_required_field_values(): void
    {
        $subcategory = $this->createSubcategory();
        $template = $this->createTemplateWithFields($subcategory);
        $supplier = $this->createSupplier();
        $this->authenticateSupplier($supplier);

        $payload = [
            'subcategory_id' => $subcategory->getKey(),
            'supplier_id' => $supplier->getKey(),
            'data_template_id' => $template->getKey(),
            'translations' => [
                'en' => [
                    'name' => 'Incomplete Family',
                ],
            ],
            'values' => [
                'lighting_template_option' => 'One',
            ],
        ];

        $response = $this->postJson('/api/families', $payload);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
        $response->assertJsonValidationErrors(['values.model_number']);
    }

    public function test_supplier_can_update_family_values(): void
    {
        $subcategory = $this->createSubcategory();
        $template = $this->createTemplateWithFields($subcategory);
        $supplier = $this->createSupplier();
        $this->authenticateSupplier($supplier);

        $family = $this->postJson('/api/families', [
            'subcategory_id' => $subcategory->getKey(),
            'supplier_id' => $supplier->getKey(),
            'data_template_id' => $template->getKey(),
            'translations' => [
                'en' => [
                    'name' => 'Lighting Family',
                ],
            ],
            'values' => [
                'model_number' => 'MN-100',
                'lighting_template_option' => 'One',
            ],
        ])->json('data');

        $response = $this->putJson('/api/families/' . $family['id'], [
            'values' => [
                'model_number' => 'MN-200',
                'lighting_template_option' => 'Two',
            ],
        ]);

        $response->assertOk();
        $response->assertJsonPath('data.values.0.value', 'MN-200');
        $response->assertJsonPath('data.values.1.value', 'Two');

        $this->assertDatabaseHas('family_field_values', [
            'family_id' => $family['id'],
        ]);
    }

    public function test_switching_template_requires_new_values(): void
    {
        $subcategory = $this->createSubcategory();
        $template = $this->createTemplateWithFields($subcategory, 'lighting_template');
        $newTemplate = $this->createTemplateWithFields($subcategory, 'alternative_template');
        $supplier = $this->createSupplier();
        $this->authenticateSupplier($supplier);

        $family = $this->postJson('/api/families', [
            'subcategory_id' => $subcategory->getKey(),
            'supplier_id' => $supplier->getKey(),
            'data_template_id' => $template->getKey(),
            'translations' => [
                'en' => [
                    'name' => 'Lighting Family',
                ],
            ],
            'values' => [
                'model_number' => 'MN-100',
                'lighting_template_option' => 'One',
            ],
        ])->json('data');

        $response = $this->putJson('/api/families/' . $family['id'], [
            'data_template_id' => $newTemplate->getKey(),
        ]);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
        $response->assertJsonValidationErrors(['values']);
    }

    public function test_it_lists_families_filtered_by_supplier(): void
    {
        $subcategory = $this->createSubcategory();
        $template = $this->createTemplateWithFields($subcategory);
        $supplierOne = $this->createSupplier('first@example.com');
        $supplierTwo = $this->createSupplier('second@example.com');

        $this->authenticateSupplier($supplierOne);
        $this->postJson('/api/families', [
            'subcategory_id' => $subcategory->getKey(),
            'supplier_id' => $supplierOne->getKey(),
            'data_template_id' => $template->getKey(),
            'translations' => [
                'en' => ['name' => 'First Family'],
            ],
            'values' => [
                'model_number' => 'ONE',
                'lighting_template_option' => 'One',
            ],
        ]);

        $this->authenticateSupplier($supplierTwo);
        $this->postJson('/api/families', [
            'subcategory_id' => $subcategory->getKey(),
            'supplier_id' => $supplierTwo->getKey(),
            'data_template_id' => $template->getKey(),
            'translations' => [
                'en' => ['name' => 'Second Family'],
            ],
            'values' => [
                'model_number' => 'TWO',
                'lighting_template_option' => 'Two',
            ],
        ]);

        $response = $this->getJson('/api/families/subcategories/' . $subcategory->getKey() . '?supplier_id=' . $supplierOne->getKey());

        $response->assertOk();
        $this->assertCount(1, $response->json('data.data'));
        $this->assertSame('First Family', $response->json('data.data.0.name'));
    }

    private function authenticateSupplier(Supplier $supplier): CompanyUser
    {
        $user = CompanyUser::create([
            'name' => 'Supplier User',
            'email' => Str::uuid() . '@example.com',
            'password' => 'password',
            'company_id' => $supplier->company_id,
        ]);

        $this->actingAs($user, 'company');

        return $user;
    }

    private function createSubcategory(): Subcategory
    {
        $solution = new Solution();
        $solution->save();
        $solution->translations()->create([
            'name' => 'Infrastructure',
            'locale' => 'en',
        ]);

        $department = new Department([
            'solution_id' => $solution->getKey(),
        ]);
        $department->save();
        $department->translations()->create([
            'name' => 'Networking',
            'locale' => 'en',
        ]);

        $subcategory = new Subcategory([
            'department_id' => $department->getKey(),
        ]);
        $subcategory->save();
        $subcategory->translations()->create([
            'name' => 'Switches',
            'locale' => 'en',
        ]);

        return $subcategory->refresh();
    }

    private function createTemplateWithFields(Subcategory $subcategory, string $slugPrefix = 'lighting_template'): DataTemplate
    {
        $template = new DataTemplate([
            'subcategory_id' => $subcategory->getKey(),
            'type' => DataTemplateType::FAMILY,
        ]);
        $template->save();
        $template->translations()->create([
            'locale' => 'en',
            'name' => ucfirst(str_replace('_', ' ', $slugPrefix)),
        ]);

        $requiredField = new DataField([
            'data_template_id' => $template->getKey(),
            'slug' => 'model_number',
            'type' => DataFieldType::TEXT,
            'is_required' => true,
            'position' => 1,
        ]);
        $requiredField->save();
        $requiredField->translations()->create([
            'locale' => 'en',
            'label' => 'Model Number',
        ]);

        $selectField = new DataField([
            'data_template_id' => $template->getKey(),
            'slug' => $slugPrefix . '_option',
            'type' => DataFieldType::SELECT,
            'options' => ['One', 'Two'],
            'position' => 2,
        ]);
        $selectField->save();
        $selectField->translations()->create([
            'locale' => 'en',
            'label' => 'Options',
        ]);

        return $template->refresh()->load('fields');
    }

    private function createSupplier(string $email = null): Supplier
    {
        $company = Company::create([
            'name' => 'Supplier ' . Str::random(5),
            'contact_email' => $email ?? Str::uuid() . '@example.com',
            'type' => CompanyType::SUPPLIER,
        ]);

        $supplier = new Supplier([
            'company_id' => $company->getKey(),
        ]);
        $supplier->save();

        return $supplier->refresh();
    }
}
