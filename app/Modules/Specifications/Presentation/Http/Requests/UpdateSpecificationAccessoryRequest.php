<?php

namespace App\Modules\Specifications\Presentation\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Modules\Specifications\Application\DTOs\SpecificationAccessoryUpdateInput;

class UpdateSpecificationAccessoryRequest extends FormRequest
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
            'quantity' => ['sometimes', 'nullable', 'integer', 'min:1'],
            'accessory_type' => ['sometimes', 'nullable', 'string'],
        ];
    }

    public function toInput(): SpecificationAccessoryUpdateInput
    {
        return SpecificationAccessoryUpdateInput::fromArray($this->validated());
    }
}


