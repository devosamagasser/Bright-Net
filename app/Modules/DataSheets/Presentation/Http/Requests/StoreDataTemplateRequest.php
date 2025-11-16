<?php

namespace App\Modules\DataSheets\Presentation\Http\Requests;

use Illuminate\Support\Arr;
use Illuminate\Validation\Rules\Enum;
use Illuminate\Foundation\Http\FormRequest;
use App\Modules\DataSheets\Domain\ValueObjects\{DataFieldType, DataTemplateType};

class StoreDataTemplateRequest extends FormRequest
{
    protected function prepareForValidation(): void
    {
        $routeType = $this->route('data_template_type');

        if ($routeType) {
            $this->merge(['type' => $routeType]);
        }

        $fields = $this->input('fields');

        if (is_array($fields)) {
            $normalizedFields = array_map(function ($field) {

                if (isset($field['depends_on_value']) && ! isset($field['depends_on_values'])) {
                    $field['depends_on_values'] = Arr::wrap($field['depends_on_value']);
                }

                if (isset($field['depends_on_values']) && ! is_array($field['depends_on_values'])) {
                    $field['depends_on_values'] = Arr::wrap($field['depends_on_values']);
                }

                return $field;
            }, $fields);

            $this->merge(['fields' => $normalizedFields]);
        }
    }

    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'subcategory_id' => ['required', 'integer', 'exists:subcategories,id'],
            'type' => ['required', 'string', new Enum(DataTemplateType::class)],
            'translations' => ['required', 'array', 'min:1'],
            'translations.*.name' => ['required', 'string', 'min:1', 'max:255'],
            'translations.*.description' => ['nullable', 'string'],
            'fields' => ['required', 'array', 'min:1'],
            'fields.*.name' => ['required', 'string', 'min:1', 'max:255', 'distinct'],
            'fields.*.type' => ['required', 'string', new Enum(DataFieldType::class)],
            'fields.*.is_required' => ['sometimes', 'boolean'],
            'fields.*.is_filterable' => ['sometimes', 'boolean'],
            'fields.*.options' => ['nullable', 'array'],
            'fields.*.options.*' => ['nullable', 'string'],
            'fields.*.position' => ['nullable', 'integer', 'min:1'],
            'fields.*.is_depended' => ['sometimes', 'boolean'],
            'fields.*.depends_on_field' => ['required_if:fields.*.is_depended,true', 'string', 'min:1', 'max:255'],
            'fields.*.depends_on_values' => ['required_if:fields.*.is_depended,true', 'array', 'min:1'],
            'fields.*.depends_on_values.*' => ['string', 'min:1'],
            'fields.*.translations' => ['required', 'array', 'min:1'],
            'fields.*.translations.*.label' => ['required', 'string', 'min:1', 'max:255'],
            'fields.*.translations.*.placeholder' => ['nullable', 'string', 'max:255'],
        ];
    }

    public function withValidator($validator): void
    {
        $validator->after(function ($validator) {
            $fields = $this->input('fields', []);
            $seenSlugs = [];
            $fieldNames = collect($fields)
                ->map(fn ($field) => $field['name'] ?? null)
                ->filter()
                ->all();

            foreach ($fields as $index => $field) {
                $type = $field['type'] ?? null;
                $fieldType = is_string($type) ? DataFieldType::tryFrom($type) : null;

                if ($fieldType?->requiresOptions()) {
                    $options = $field['options'] ?? null;
                    if (empty($options) || ! is_array($options)) {
                        $validator->errors()->add(
                            "fields.$index.options",
                            trans('validation.required', ['attribute' => 'options'])
                        );
                    }
                }

                $isDepended = filter_var($field['is_depended'] ?? false, FILTER_VALIDATE_BOOLEAN);

                if ($isDepended) {
                    $dependsOnField = $field['depends_on_field'] ?? null;
                    $currentName = $field['name'] ?? null;

                    if ($dependsOnField && ! in_array($dependsOnField, $fieldNames, true)) {
                        $validator->errors()->add(
                            "fields.$index.depends_on_field",
                            trans('validation.exists', ['attribute' => 'depends_on_field'])
                        );
                    }

                    if ($dependsOnField && $currentName && $dependsOnField === $currentName) {
                        $validator->errors()->add(
                            "fields.$index.depends_on_field",
                            trans('validation.different', ['attribute' => 'depends_on_field', 'other' => 'name'])
                        );
                    }
                }
            }
        });
    }
}
