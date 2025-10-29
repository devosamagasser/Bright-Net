<?php

namespace App\Modules\Families\Presentation\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Modules\Shared\Support\Helper\RequestValidationBuilder;

class StoreFamilyRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $keys = [
            'subcategory_id' => ['required', 'integer', 'exists:subcategories,id'],
            'data_template_id' => ['required', 'integer', 'exists:data_templates,id'],
            'name' => ['required', 'string', 'min:1', 'max:255'],
            'translations' => ['required', 'array', 'min:1'],
            'translations.*.description' => ['nullable', 'string'],
            'values' => ['required', 'array']
        ];

        return array_merge(
            $keys,
            RequestValidationBuilder::build($this->input('data_template_id'))
        );
    }

}
