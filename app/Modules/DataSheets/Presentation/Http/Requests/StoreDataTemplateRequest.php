<?php

namespace App\Modules\DataSheets\Presentation\Http\Requests;

use Illuminate\Validation\Rules\Enum;
use Illuminate\Foundation\Http\FormRequest;
use App\Modules\DataSheets\Domain\ValueObjects\{DataFieldType, DataTemplateType};

class StoreDataTemplateRequest extends FormRequest
{
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
            'fields.*.slug' => ['required', 'string', 'max:255', 'regex:/^[A-Za-z0-9_\-]+$/'],
            'fields.*.type' => ['required', 'string', new Enum(DataFieldType::class)],
            'fields.*.is_required' => ['sometimes', 'boolean'],
            'fields.*.is_filterable' => ['sometimes', 'boolean'],
            'fields.*.options' => ['nullable', 'array'],
            'fields.*.options.*' => ['nullable', 'string'],
            'fields.*.position' => ['nullable', 'integer', 'min:1'],
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

            foreach ($fields as $index => $field) {
                $slug = $field['slug'] ?? null;
                if ($slug) {
                    if (in_array($slug, $seenSlugs, true)) {
                        $validator->errors()->add("fields.$index.slug", trans('validation.distinct', ['attribute' => 'slug']));
                    }
                    $seenSlugs[] = $slug;
                }

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
            }
        });
    }
}
