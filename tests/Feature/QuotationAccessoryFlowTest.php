<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Http\Response;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Modules\DataSheets\Domain\Models\{DataField, DataTemplate};
use App\Modules\DataSheets\Domain\ValueObjects\{DataFieldType, DataTemplateType};
use App\Modules\Subcategories\Domain\Models\Subcategory;
use App\Modules\Departments\Domain\Models\Department;
use App\Modules\SolutionsCatalog\Domain\Models\Solution;
use App\Modules\Companies\Domain\Models\Company;
use App\Modules\Companies\Domain\ValueObjects\CompanyType;
use App\Models\Supplier;
use App\Models\CompanyUser;
use App\Modules\Families\Domain\Models\Family;
use App\Modules\Families\Domain\Models\FamilyTranslation;
use App\Modules\Families\Domain\Models\FamilyFieldValue;

class QuotationAccessoryFlowTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        config(['auth.defaults.guard' => 'company']);
        Auth::shouldUse('company');
    }

    public function test_needed_accessories_are_added_automatically_and_optionals_require_link(): void
    {
        $catalog = $this->prepareCatalog();

        $optionalProduct = $this->createProductForFamily($catalog['family'], $catalog['productTemplate'], 'OPT-100');
        $neededProduct = $this->createProductForFamily($catalog['family'], $catalog['productTemplate'], 'NEED-100');

        $mainProduct = $this->createProductForFamily($catalog['family'], $catalog['productTemplate'], 'MAIN-100', [
            [
                'code' => $optionalProduct['code'],
                'type' => 'optional',
                'quantity' => 1,
            ],
            [
                'code' => $neededProduct['code'],
                'type' => 'needed',
                'quantity' => 1,
            ],
        ]);

        $response = $this->postJson('/api/quotations/draft/items', [
            'product_id' => $mainProduct['id'],
            'quantity' => 1,
            'accessories' => [
                [
                    'accessory_id' => $optionalProduct['id'],
                    'accessory_type' => 'optional',
                    'quantity' => 1,
                ],
            ],
        ]);

        $response->assertStatus(Response::HTTP_CREATED);

        $types = collect($response->json('data.products.0.accessories'))->pluck('type');

        $this->assertTrue($types->contains('needed'));
        $this->assertTrue($types->contains('optional'));

        $quotationId = $response->json('data.id');

        $this->assertDatabaseHas('quotation_product_accessories', [
            'quotation_id' => $quotationId,
            'product_id' => $neededProduct['id'],
            'accessory_type' => 'needed',
        ]);

        $this->assertDatabaseHas('quotation_product_accessories', [
            'quotation_id' => $quotationId,
            'product_id' => $optionalProduct['id'],
            'accessory_type' => 'optional',
        ]);
    }

    public function test_unlinked_optional_accessory_is_rejected(): void
    {
        $catalog = $this->prepareCatalog();

        $linkedOptional = $this->createProductForFamily($catalog['family'], $catalog['productTemplate'], 'OPT-200');
        $neededProduct = $this->createProductForFamily($catalog['family'], $catalog['productTemplate'], 'NEED-200');
        $unlinkedAccessory = $this->createProductForFamily($catalog['family'], $catalog['productTemplate'], 'UNL-200');

        $mainProduct = $this->createProductForFamily($catalog['family'], $catalog['productTemplate'], 'MAIN-200', [
            [
                'code' => $linkedOptional['code'],
                'type' => 'optional',
            ],
            [
                'code' => $neededProduct['code'],
                'type' => 'needed',
            ],
        ]);

        $response = $this->postJson('/api/quotations/draft/items', [
            'product_id' => $mainProduct['id'],
            'quantity' => 1,
            'accessories' => [
                [
                    'accessory_id' => $unlinkedAccessory['id'],
                    'accessory_type' => 'optional',
                ],
            ],
        ]);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
        $response->assertJsonValidationErrors(['accessories.0.accessory_id']);
    }

    public function test_supplier_can_attach_accessory_using_dedicated_endpoint(): void
    {
        $catalog = $this->prepareCatalog();

        $mainProduct = $this->createProductForFamily($catalog['family'], $catalog['productTemplate'], 'MAIN-300');
        $accessory = $this->createProductForFamily($catalog['family'], $catalog['productTemplate'], 'ACC-300');

        $response = $this->postJson('/api/products/' . $mainProduct['id'] . '/accessories', [
            'accessory_id' => $accessory['id'],
            'accessory_type' => 'optional',
            'quantity' => 2,
        ]);

        $response->assertStatus(Response::HTTP_CREATED);

        $this->assertDatabaseHas('product_accessories', [
            'product_id' => $mainProduct['id'],
            'accessory_id' => $accessory['id'],
            'accessory_type' => 'optional',
            'quantity' => '2',
        ]);
    }

    /**
     * @return array{family: array{id:int, name:string}, productTemplate: DataTemplate}
     */
    private function prepareCatalog(): array
    {
        $subcategory = $this->createSubcategory();
        $familyTemplate = $this->createTemplateWithFields($subcategory, DataTemplateType::FAMILY, 'family_template');
        $productTemplate = $this->createTemplateWithFields($subcategory, DataTemplateType::PRODUCT, 'product_template');
        $supplier = $this->createSupplier();
        $this->authenticateSupplier($supplier);
        $family = $this->createFamily($subcategory, $familyTemplate, $supplier);

        return [
            'family' => $family,
            'productTemplate' => $productTemplate,
        ];
    }

    /**
     * @param  array{id:int, name:string}  $family
     * @param  array<int, array<string, mixed>>|null  $accessories
     * @return array<string, mixed>
     */
    private function createProductForFamily(array $family, DataTemplate $template, string $code, ?array $accessories = null): array
    {
        $payload = [
            'family_id' => $family['id'],
            'data_template_id' => $template->getKey(),
            'code' => $code,
            'stock' => 5,
            'translations' => [
                'en' => [
                    'name' => $code,
                    'description' => $code . ' description',
                ],
            ],
            'values' => [
                'model_number' => $code,
            ],
            'prices' => [
                [
                    'price' => 100,
                    'from' => 1,
                    'to' => 10,
                    'currency' => 'USD',
                    'delivery_time_unit' => 'days',
                    'delivery_time_value' => '5',
                    'vat_status' => true,
                ],
            ],
        ];

        if ($accessories !== null) {
            $payload['accessories'] = $accessories;
        }

        return $this->postJson('/api/products', $payload)
            ->assertStatus(Response::HTTP_CREATED)
            ->json('data');
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
     * @return array{id:int, name:string}
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
}
