<?php

namespace App\Modules\Families\Presentation\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreFamilyRequest extends FormRequest
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
            'supplier_id' => ['required', 'integer', 'exists:suppliers,id'],
            'data_template_id' => ['required', 'integer', 'exists:data_templates,id'],
            'translations' => ['required', 'array', 'min:1'],
            'translations.*.name' => ['required', 'string', 'min:1', 'max:255'],
            'translations.*.description' => ['nullable', 'string'],
            'values' => ['required', 'array'],
        ];
    }
}
