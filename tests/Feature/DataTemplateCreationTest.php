<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Http\Response;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Modules\DataSheets\Domain\Models\DataTemplate;
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
}
