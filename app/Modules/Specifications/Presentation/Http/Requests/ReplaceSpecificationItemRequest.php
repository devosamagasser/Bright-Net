<?php

namespace App\Modules\Specifications\Presentation\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Modules\Specifications\Application\DTOs\SpecificationItemInput;

class ReplaceSpecificationItemRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'product_id' => ['required', 'integer', 'exists:products,id'],
            'quantity' => ['required', 'integer', 'min:1'],
            'item_ref' => ['nullable', 'string', 'max:50'],
            'notes' => ['nullable', 'string'],
            'accessories' => ['nullable', 'array'],
            'accessories.*' => ['array'],
            'accessories.*.product_id' => ['required', 'integer', 'exists:products,id'],
            'accessories.*.quantity' => ['nullable', 'integer', 'min:1'],
            'accessories.*.accessory_type' => ['nullable', 'string'],
        ];
    }

    public function toInput(): SpecificationItemInput
    {
        return SpecificationItemInput::fromArray($this->validated());
    }
}


