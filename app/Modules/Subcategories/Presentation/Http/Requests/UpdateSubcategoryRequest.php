<?php

namespace App\Modules\Subcategories\Presentation\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateSubcategoryRequest extends FormRequest
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
            'department_id' => ['sometimes', 'integer', 'exists:departments,id'],
            'translations' => ['sometimes', 'array', 'min:1'],
            'translations.*.name' => ['required_with:translations', 'string', 'min:1', 'max:255'],
        ];
    }
}
