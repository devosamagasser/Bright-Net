<?php

namespace App\Modules\Specifications\Presentation\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Modules\Specifications\Application\DTOs\SpecificationItemUpdateInput;

class UpdateSpecificationItemRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'item_ref' => ['sometimes', 'nullable', 'string', 'max:50'],
            'position' => ['sometimes', 'nullable', 'integer', 'min:1'],
            'notes' => ['sometimes', 'nullable', 'string'],
            'description' => ['sometimes', 'nullable', 'string'],
            'quantity' => ['sometimes', 'nullable', 'integer', 'min:1'],
        ];
    }

    public function toInput(): SpecificationItemUpdateInput
    {
        return SpecificationItemUpdateInput::fromArray($this->validated());
    }
}


