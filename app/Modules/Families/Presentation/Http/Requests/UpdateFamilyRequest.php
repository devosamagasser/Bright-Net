<?php

namespace App\Modules\Families\Presentation\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateFamilyRequest extends FormRequest
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
            'subcategory_id' => ['sometimes', 'integer', 'exists:subcategories,id'],
            'supplier_id' => ['sometimes', 'integer', 'exists:suppliers,id'],
            'data_template_id' => ['sometimes', 'integer', 'exists:data_templates,id'],
            'translations' => ['sometimes', 'array', 'min:1'],
            'translations.*.name' => ['required_with:translations', 'string', 'min:1', 'max:255'],
            'translations.*.description' => ['nullable', 'string'],
            'values' => ['sometimes', 'array'],
        ];
    }
}
