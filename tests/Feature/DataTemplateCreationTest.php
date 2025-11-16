<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Http\Response;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Modules\DataSheets\Domain\Models\{DataField, DataTemplate};
use App\Modules\DataSheets\Domain\ValueObjects\{DataFieldType, DataTemplateType};
use App\Modules\Departments\Domain\Models\Department;
use App\Modules\Subcategories\Domain\Models\Subcategory;
use App\Modules\SolutionsCatalog\Domain\Models\Solution;

class DataTemplateCreationTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_creates_a_data_template_with_fields(): void
    {
        $subcategory = $this->createSubcategory();

        $payload = [
            'subcategory_id' => $subcategory->getKey(),
            'type' => DataTemplateType::FAMILY->value,
            'translations' => [
                'en' => [
                    'name' => 'Lighting Family',
                    'description' => 'Details about lighting products.',
                ],
            ],
            'fields' => [
                [
                    'slug' => 'model_number',
                    'type' => DataFieldType::TEXT->value,
                    'is_required' => true,
                    'is_filterable' => true,
                    'translations' => [
                        'en' => [
                            'label' => 'Model Number',
                            'placeholder' => 'Enter model number',
                        ],
                    ],
                ],
                [
                    'slug' => 'available_colors',
                    'type' => DataFieldType::SELECT->value,
                    'options' => ['Red', 'Blue'],
                    'translations' => [
                        'en' => [
                            'label' => 'Available Colors',
                            'placeholder' => 'Choose colors',
                        ],
                    ],
                ],
            ],
        ];

        $response = $this->postJson(route('api.data-templates.store'), $payload);

        $response->assertStatus(Response::HTTP_CREATED);
        $response->assertJsonPath('data.type', DataTemplateType::FAMILY->value);
        $response->assertJsonPath('data.fields.0.slug', 'model_number');
        $response->assertJsonPath('data.fields.1.options', ['Red', 'Blue']);

        $this->assertDatabaseHas('data_templates', [
            'subcategory_id' => $subcategory->getKey(),
            'type' => DataTemplateType::FAMILY->value,
        ]);

        $this->assertDatabaseHas('data_fields', [
            'slug' => 'available_colors',
            'type' => DataFieldType::SELECT->value,
        ]);

        $template = DataTemplate::first();
        $this->assertEquals('Lighting Family', $template->translate('en')->name);
        $this->assertCount(2, $template->fields);
    }

    public function test_it_creates_a_family_data_template_without_explicit_type(): void
    {
        $subcategory = $this->createSubcategory();

        $payload = [
            'subcategory_id' => $subcategory->getKey(),
            'translations' => [
                'en' => [
                    'name' => 'Family Template',
                ],
            ],
            'fields' => [
                [
                    'slug' => 'model_number',
                    'type' => DataFieldType::TEXT->value,
                    'translations' => [
                        'en' => [
                            'label' => 'Model Number',
                        ],
                    ],
                ],
            ],
        ];

        $response = $this->postJson(route('api.family-data-templates.store'), $payload);

        $response->assertStatus(Response::HTTP_CREATED);
        $response->assertJsonPath('data.type', DataTemplateType::FAMILY->value);

        $this->assertDatabaseHas('data_templates', [
            'subcategory_id' => $subcategory->getKey(),
            'type' => DataTemplateType::FAMILY->value,
        ]);
    }

    public function test_it_requires_options_for_select_like_fields(): void
    {
        $subcategory = $this->createSubcategory();

        $payload = [
            'subcategory_id' => $subcategory->getKey(),
            'type' => DataTemplateType::PRODUCT->value,
            'translations' => [
                'en' => [
                    'name' => 'Product Template',
                ],
            ],
            'fields' => [
                [
                    'slug' => 'color',
                    'type' => DataFieldType::SELECT->value,
                    'translations' => [
                        'en' => [
                            'label' => 'Color',
                        ],
                    ],
                ],
            ],
        ];

        $response = $this->postJson(route('api.data-templates.store'), $payload);

        $response->assertUnprocessable();
        $response->assertJsonValidationErrors(['fields.0.options']);
    }

    public function test_it_lists_data_templates(): void
    {
        $subcategory = $this->createSubcategory();
        $this->createTemplateWithFields($subcategory);
        $this->createTemplateWithFields($subcategory, 'product_template', DataTemplateType::PRODUCT);

        $response = $this->getJson(route('api.data-templates.index'));

        $response->assertOk();
        $response->assertJsonPath('data.data.0.fields.0.slug', 'model_number');
        $this->assertArrayHasKey('meta', $response->json('data'));
    }

    public function test_family_routes_only_return_family_templates(): void
    {
        $subcategory = $this->createSubcategory();
        $this->createTemplateWithFields($subcategory);
        $this->createTemplateWithFields($subcategory, 'product_template', DataTemplateType::PRODUCT);

        $response = $this->getJson(route('api.family-data-templates.index'));

        $response->assertOk();
        $types = collect($response->json('data.data'))
            ->pluck('type')
            ->unique()
            ->all();

        $this->assertSame([DataTemplateType::FAMILY->value], $types);
    }

    public function test_family_routes_restrict_access_to_product_templates(): void
    {
        $subcategory = $this->createSubcategory();
        $productTemplate = $this->createTemplateWithFields($subcategory, 'product_template', DataTemplateType::PRODUCT);

        $response = $this->getJson(route('api.family-data-templates.show', $productTemplate->getKey()));

        $response->assertNotFound();
    }

    public function test_it_creates_a_product_data_template_without_explicit_type(): void
    {
        $subcategory = $this->createSubcategory();

        $payload = [
            'subcategory_id' => $subcategory->getKey(),
            'translations' => [
                'en' => [
                    'name' => 'Product Template',
                ],
            ],
            'fields' => [
                [
                    'slug' => 'sku',
                    'type' => DataFieldType::TEXT->value,
                    'translations' => [
                        'en' => [
                            'label' => 'SKU',
                        ],
                    ],
                ],
            ],
        ];

        $response = $this->postJson(route('api.product-data-templates.store'), $payload);

        $response->assertStatus(Response::HTTP_CREATED);
        $response->assertJsonPath('data.type', DataTemplateType::PRODUCT->value);

        $this->assertDatabaseHas('data_templates', [
            'subcategory_id' => $subcategory->getKey(),
            'type' => DataTemplateType::PRODUCT->value,
        ]);
    }

    public function test_it_shows_a_single_data_template(): void
    {
        $subcategory = $this->createSubcategory();
        $template = $this->createTemplateWithFields($subcategory);

        $response = $this->getJson(route('api.data-templates.show', $template->getKey()));

        $response->assertOk();
        $response->assertJsonPath('data.id', $template->getKey());
        $response->assertJsonPath('data.fields.0.slug', 'model_number');
    }

    public function test_it_updates_a_data_template(): void
    {
        $subcategory = $this->createSubcategory();
        $template = $this->createTemplateWithFields($subcategory);
        $field = $template->fields->first();

        $payload = [
            'subcategory_id' => $subcategory->getKey(),
            'type' => DataTemplateType::FAMILY->value,
            'translations' => [
                'en' => [
                    'name' => 'Updated Template',
                    'description' => 'Updated description.',
                ],
            ],
            'fields' => [
                [
                    'id' => $field->getKey(),
                    'slug' => 'model_number',
                    'type' => DataFieldType::TEXT->value,
                    'is_required' => false,
                    'translations' => [
                        'en' => [
                            'label' => 'Model',
                            'placeholder' => 'Model placeholder',
                        ],
                    ],
                ],
                [
                    'slug' => 'warranty_period',
                    'type' => DataFieldType::NUMBER->value,
                    'translations' => [
                        'en' => [
                            'label' => 'Warranty Period',
                            'placeholder' => 'Enter warranty',
                        ],
                    ],
                ],
            ],
        ];

        $response = $this->putJson(route('api.data-templates.update', $template->getKey()), $payload);

        $response->assertOk();
        $response->assertJsonPath('data.fields.1.slug', 'warranty_period');
        $this->assertDatabaseHas('data_templates', [
            'id' => $template->getKey(),
        ]);
        $this->assertDatabaseHas('data_fields', [
            'slug' => 'warranty_period',
        ]);
        $this->assertDatabaseMissing('data_fields', [
            'id' => $template->fields->last()->getKey(),
        ]);
    }

    public function test_it_updates_field_dependencies(): void
    {
        $subcategory = $this->createSubcategory();
        $template = new DataTemplate([
            'subcategory_id' => $subcategory->getKey(),
            'type' => DataTemplateType::FAMILY,
        ]);
        $template->save();
        $template->translations()->create([
            'locale' => 'en',
            'name' => 'Shape Template',
        ]);

        $shapeField = new DataField([
            'data_template_id' => $template->getKey(),
            'slug' => 'shape',
            'type' => DataFieldType::SELECT,
            'options' => ['Linear', 'Round'],
            'is_required' => true,
            'position' => 1,
        ]);
        $shapeField->save();
        $shapeField->translations()->create([
            'locale' => 'en',
            'label' => 'Shape',
        ]);
        $shapeFieldName = $shapeField->refresh()->name;

        $lengthField = new DataField([
            'data_template_id' => $template->getKey(),
            'slug' => 'length',
            'type' => DataFieldType::NUMBER,
            'is_required' => true,
            'position' => 2,
        ]);
        $lengthField->save();
        $lengthField->translations()->create([
            'locale' => 'en',
            'label' => 'Length',
        ]);

        $payload = [
            'subcategory_id' => $subcategory->getKey(),
            'type' => DataTemplateType::FAMILY->value,
            'translations' => [
                'en' => [
                    'name' => 'Shape Template',
                ],
            ],
            'fields' => [
                [
                    'id' => $shapeField->getKey(),
                    'slug' => 'shape',
                    'type' => DataFieldType::SELECT->value,
                    'options' => ['Linear', 'Round'],
                    'is_required' => true,
                    'translations' => [
                        'en' => [
                            'label' => 'Shape',
                        ],
                    ],
                ],
                [
                    'id' => $lengthField->getKey(),
                    'slug' => 'length',
                    'type' => DataFieldType::NUMBER->value,
                    'is_required' => true,
                    'translations' => [
                        'en' => [
                            'label' => 'Length',
                        ],
                    ],
                    'dependencies' => [
                        [
                            'depends_on_field_id' => $shapeField->getKey(),
                            'values' => ['Linear'],
                        ],
                    ],
                ],
            ],
        ];

        $response = $this->putJson(route('api.data-templates.update', $template->getKey()), $payload);

        $response->assertOk();
        $response->assertJsonPath('data.fields.1.dependencies.0.values', ['Linear']);
        $response->assertJsonPath('data.fields.1.is_dependent', true);
        $response->assertJsonPath('data.fields.1.depends_on_field_name', $shapeFieldName);
        $response->assertJsonPath('data.fields.1.depends_on_values', ['Linear']);

        $this->assertDatabaseHas('depended_fields', [
            'data_field_id' => $lengthField->getKey(),
            'depends_on_field_id' => $shapeField->getKey(),
        ]);
    }

    public function test_it_deletes_a_data_template(): void
    {
        $subcategory = $this->createSubcategory();
        $template = $this->createTemplateWithFields($subcategory);

        $response = $this->deleteJson(route('api.data-templates.destroy', $template->getKey()));

        $response->assertNoContent();
        $this->assertDatabaseMissing('data_templates', [
            'id' => $template->getKey(),
        ]);
        $this->assertDatabaseCount('data_fields', 0);
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

    private function createTemplateWithFields(Subcategory $subcategory, string $slugPrefix = 'lighting_template', DataTemplateType $type = DataTemplateType::FAMILY): DataTemplate
    {
        $template = new DataTemplate([
            'subcategory_id' => $subcategory->getKey(),
            'type' => $type,
        ]);
        $template->save();
        $template->translations()->create([
            'locale' => 'en',
            'name' => ucfirst(str_replace('_', ' ', $slugPrefix)),
            'description' => 'Description',
        ]);

        $fieldOne = new DataField([
            'data_template_id' => $template->getKey(),
            'slug' => 'model_number',
            'type' => DataFieldType::TEXT,
            'is_required' => true,
            'position' => 1,
        ]);
        $fieldOne->save();
        $fieldOne->translations()->create([
            'locale' => 'en',
            'label' => 'Model Number',
            'placeholder' => 'Enter model',
        ]);

        $fieldTwo = new DataField([
            'data_template_id' => $template->getKey(),
            'slug' => $slugPrefix . '_option',
            'type' => DataFieldType::SELECT,
            'options' => ['One', 'Two'],
            'position' => 2,
        ]);
        $fieldTwo->save();
        $fieldTwo->translations()->create([
            'locale' => 'en',
            'label' => 'Options',
            'placeholder' => 'Select option',
        ]);

        return $template->refresh()->load('fields');
    }
}
