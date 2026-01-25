<?php

namespace Tests\Feature;

use App\Models\CompanyUser;
use App\Models\Supplier;
use App\Modules\Companies\Domain\Models\Company;
use App\Modules\Companies\Domain\ValueObjects\CompanyType;
use App\Modules\DataSheets\Domain\Models\{DataField, DataTemplate};
use App\Modules\DataSheets\Domain\ValueObjects\{DataFieldType, DataTemplateType};
use App\Modules\Departments\Domain\Models\Department;
use App\Modules\Families\Domain\Models\Family;
use App\Modules\Families\Domain\Models\FamilyFieldValue;
use App\Modules\Families\Domain\Models\FamilyTranslation;
use App\Modules\SolutionsCatalog\Domain\Models\Solution;
use App\Modules\Subcategories\Domain\Models\Subcategory;
use App\Modules\Taxonomy\Domain\Models\Color;
use App\Modules\Taxonomy\Domain\Models\ColorTranslation;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Tests\TestCase;

class ProductManagementTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        config(['auth.defaults.guard' => 'company']);
        Auth::shouldUse('company');
    }

    public function test_supplier_can_create_product_with_pricing_and_accessories(): void
    {
        $subcategory = $this->createSubcategory();
        $familyTemplate = $this->createTemplateWithFields($subcategory, DataTemplateType::FAMILY, 'family_template');
        $productTemplate = $this->createTemplateWithFields($subcategory, DataTemplateType::PRODUCT, 'product_template');
        $supplier = $this->createSupplier();
        $this->authenticateSupplier($supplier);
        $family = $this->createFamily($subcategory, $familyTemplate, $supplier);
        $color = $this->createColor();

        $accessoryProduct = $this->postJson('/api/products', [
            'family_id' => $family['id'],
            'data_template_id' => $productTemplate->getKey(),
            'code' => 'ACC-100',
            'stock' => 5,
            'translations' => [
                'en' => [
                    'name' => 'Accessory Product',
                    'description' => 'Accessory description',
                ],
            ],
            'values' => [
                'model_number' => 'ACC-100',
            ],
        ])->assertStatus(Response::HTTP_CREATED)
            ->json('data');

        $response = $this->postJson('/api/products', [
            'family_id' => $family['id'],
            'data_template_id' => $productTemplate->getKey(),
            'code' => 'PROD-200',
            'stock' => 15,
            'disclaimer' => 'Product disclaimer',
            'translations' => [
                'en' => [
                    'name' => 'Main Product',
                    'description' => 'Main product description',
                ],
            ],
            'values' => [
                'model_number' => 'PROD-200',
            ],
            'prices' => [
                [
                    'price' => 1000,
                    'from' => 1,
                    'to' => 10,
                    'currency' => 'USD',
                    'delivery_time_unit' => 'days',
                    'delivery_time_value' => '7',
                    'vat_status' => true,
                ],
            ],
            'accessories' => [
                [
                    'code' => $accessoryProduct['code'],
                    'type' => 'included',
                ],
            ],
            'colors' => [$color->getKey()],
        ]);

        $response->assertStatus(Response::HTTP_CREATED);
        $response->assertJsonPath('data.code', 'PROD-200');
        $response->assertJsonPath('data.prices.0.currency', 'USD');
        $response->assertJsonPath('data.accessories.included.0.code', 'ACC-100');
        $response->assertJsonPath('data.colors.0.id', $color->getKey());

        $productId = $response->json('data.id');
        $this->assertDatabaseHas('products', [
            'id' => $productId,
            'family_id' => $family['id'],
            'data_template_id' => $productTemplate->getKey(),
            'code' => 'PROD-200',
        ]);

        $this->assertDatabaseHas('product_field_values', [
            'product_id' => $productId,
            'value' => json_encode('PROD-200'),
        ]);

        $this->assertDatabaseHas('product_prices', [
            'product_id' => $productId,
            'price' => 1000,
            'currency' => 'USD',
        ]);

        $this->assertDatabaseHas('product_accessories', [
            'product_id' => $productId,
            'accessory_id' => $accessoryProduct['id'],
            'accessory_type' => 'included',
        ]);
    }

    public function test_product_creation_requires_required_field_values(): void
    {
        $subcategory = $this->createSubcategory();
        $familyTemplate = $this->createTemplateWithFields($subcategory, DataTemplateType::FAMILY, 'family_template');
        $productTemplate = $this->createTemplateWithFields($subcategory, DataTemplateType::PRODUCT, 'product_template');
        $supplier = $this->createSupplier();
        $this->authenticateSupplier($supplier);
        $family = $this->createFamily($subcategory, $familyTemplate, $supplier);

        $response = $this->postJson('/api/products', [
            'family_id' => $family['id'],
            'data_template_id' => $productTemplate->getKey(),
            'code' => 'PROD-300',
            'stock' => 10,
            'translations' => [
                'en' => [
                    'name' => 'Missing Values Product',
                ],
            ],
            'values' => [],
        ]);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
        $response->assertJsonValidationErrors(['values.model_number']);
    }

    public function test_supplier_can_update_product_and_reassign_values(): void
    {
        $subcategory = $this->createSubcategory();
        $familyTemplate = $this->createTemplateWithFields($subcategory, DataTemplateType::FAMILY, 'family_template');
        $productTemplate = $this->createTemplateWithFields($subcategory, DataTemplateType::PRODUCT, 'product_template');
        $supplier = $this->createSupplier();
        $this->authenticateSupplier($supplier);
        $family = $this->createFamily($subcategory, $familyTemplate, $supplier);

        $product = $this->postJson('/api/products', [
            'family_id' => $family['id'],
            'data_template_id' => $productTemplate->getKey(),
            'code' => 'PROD-400',
            'stock' => 20,
            'translations' => [
                'en' => [
                    'name' => 'Update Target',
                ],
            ],
            'values' => [
                'model_number' => 'PROD-400',
            ],
            'prices' => [
                [
                    'price' => 200,
                    'from' => 1,
                    'to' => 5,
                    'currency' => 'USD',
                    'delivery_time_unit' => 'days',
                    'delivery_time_value' => '3',
                    'vat_status' => true,
                ],
            ],
        ])->json('data');

        $response = $this->putJson('/api/products/' . $product['id'], [
            'stock' => 30,
            'values' => [
                'model_number' => 'PROD-401',
            ],
            'prices' => [
                [
                    'price' => 150,
                    'from' => 1,
                    'to' => 10,
                    'currency' => 'USD',
                    'delivery_time_unit' => 'weeks',
                    'delivery_time_value' => '2',
                    'vat_status' => false,
                ],
            ],
        ]);

        $response->assertOk();
        $response->assertJsonPath('data.values.0.value', 'PROD-401');
        $response->assertJsonPath('data.prices.0.delivery_time_unit', 'weeks');

        $this->assertDatabaseHas('product_field_values', [
            'product_id' => $product['id'],
            'value' => json_encode('PROD-401'),
        ]);

        $this->assertDatabaseHas('product_prices', [
            'product_id' => $product['id'],
            'price' => 150,
            'delivery_time_unit' => 'weeks',
        ]);
    }

    public function test_switching_product_template_requires_new_values(): void
    {
        $subcategory = $this->createSubcategory();
        $familyTemplate = $this->createTemplateWithFields($subcategory, DataTemplateType::FAMILY, 'family_template');
        $productTemplate = $this->createTemplateWithFields($subcategory, DataTemplateType::PRODUCT, 'product_template');
        $newTemplate = $this->createTemplateWithFields($subcategory, DataTemplateType::PRODUCT, 'alt_template');
        $supplier = $this->createSupplier();
        $this->authenticateSupplier($supplier);
        $family = $this->createFamily($subcategory, $familyTemplate, $supplier);

        $product = $this->postJson('/api/products', [
            'family_id' => $family['id'],
            'data_template_id' => $productTemplate->getKey(),
            'code' => 'PROD-500',
            'stock' => 12,
            'translations' => [
                'en' => [
                    'name' => 'Template Product',
                ],
            ],
            'values' => [
                'model_number' => 'PROD-500',
            ],
        ])->json('data');

        $response = $this->putJson('/api/products/' . $product['id'], [
            'data_template_id' => $newTemplate->getKey(),
        ]);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
        $response->assertJsonValidationErrors(['values']);
    }

    public function test_it_lists_products_filtered_by_supplier(): void
    {
        $subcategory = $this->createSubcategory();
        $familyTemplate = $this->createTemplateWithFields($subcategory, DataTemplateType::FAMILY, 'family_template');
        $productTemplate = $this->createTemplateWithFields($subcategory, DataTemplateType::PRODUCT, 'product_template');
        $supplierOne = $this->createSupplier('first@example.com');
        $supplierTwo = $this->createSupplier('second@example.com');

        $this->authenticateSupplier($supplierOne);
        $familyOne = $this->createFamily($subcategory, $familyTemplate, $supplierOne);
        $this->postJson('/api/products', [
            'family_id' => $familyOne['id'],
            'data_template_id' => $productTemplate->getKey(),
            'code' => 'SUP-1',
            'stock' => 5,
            'translations' => [
                'en' => ['name' => 'Supplier One Product'],
            ],
            'values' => [
                'model_number' => 'SUP-1',
            ],
        ]);

        $this->authenticateSupplier($supplierTwo);
        $familyTwo = $this->createFamily($subcategory, $familyTemplate, $supplierTwo);
        $this->postJson('/api/products', [
            'family_id' => $familyTwo['id'],
            'data_template_id' => $productTemplate->getKey(),
            'code' => 'SUP-2',
            'stock' => 7,
            'translations' => [
                'en' => ['name' => 'Supplier Two Product'],
            ],
            'values' => [
                'model_number' => 'SUP-2',
            ],
        ]);

        $response = $this->getJson('/api/products/families/' . $familyOne['id'] . '?supplier_id=' . $supplierOne->getKey());

        $response->assertOk();
        $this->assertCount(1, $response->json('data.data'));
        $this->assertSame('Supplier One Product', $response->json('data.data.0.name'));
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

    private function createTemplateWithFields(Subcategory $subcategory, DataTemplateType $type, string $slugPrefix): DataTemplate
    {
        $template = new DataTemplate([
            'subcategory_id' => $subcategory->getKey(),
            'type' => $type,
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

    /**
     * @return array<string, mixed>
     */
    private function createFamily(Subcategory $subcategory, DataTemplate $template, Supplier $supplier): array
    {
        $family = new Family([
            'subcategory_id' => $subcategory->getKey(),
            'supplier_id' => $supplier->getKey(),
            'data_template_id' => $template->getKey(),
            'name' => 'Family ' . Str::random(4),
        ]);
        $family->save();
        $family->translations()->saveMany([
            new FamilyTranslation([
                'locale' => 'en',
                'description' => 'Family description',
            ]),
        ]);

        FamilyFieldValue::create([
            'family_id' => $family->getKey(),
            'data_field_id' => $template->fields->first()->getKey(),
            'value' => 'FAM-001',
        ]);

        return [
            'id' => $family->getKey(),
            'name' => $family->name,
        ];
    }

    private function createColor(): Color
    {
        $color = new Color([
            'hex_code' => '#ffffff',
        ]);
        $color->save();
        $color->translations()->save(
            new ColorTranslation([
                'locale' => 'en',
                'name' => 'White',
            ])
        );

        return $color->refresh();
    }
}
