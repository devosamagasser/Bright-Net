<?php

namespace App\Modules\Departments\Presentation\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateDepartmentRequest extends FormRequest
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
            'solution_id' => ['sometimes', 'integer', 'exists:solutions,id'],
            'translations' => ['sometimes', 'array', 'min:1'],
            'translations.*.name' => ['required_with:translations', 'string', 'min:1', 'max:255'],
        ];
    }
}
