<?php

namespace App\Modules\Specifications\Presentation\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Modules\Specifications\Application\DTOs\SpecificationInput;

class UpdateSpecificationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'reference' => ['sometimes', 'nullable', 'string', 'max:100'],
            'title' => ['sometimes', 'nullable', 'string', 'max:150'],
            'company_id' => ['sometimes', 'nullable', 'integer', 'exists:companies,id'],
            'general_notes' => ['sometimes', 'nullable', 'array'],
            'show_quantity' => ['sometimes', 'nullable', 'boolean'],
            'show_approval' => ['sometimes', 'nullable', 'boolean'],
            'show_reference' => ['sometimes', 'nullable', 'boolean'],
        ];
    }

    public function toInput(): SpecificationInput
    {
        return SpecificationInput::fromArray($this->validated());
    }
}


