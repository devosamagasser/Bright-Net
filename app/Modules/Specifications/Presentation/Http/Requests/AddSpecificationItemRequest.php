<?php

namespace App\Modules\Specifications\Presentation\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Modules\Specifications\Application\DTOs\SpecificationItemInput;

class AddSpecificationItemRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'product_id' => ['required', 'integer'],
            'quantity' => ['required', 'integer', 'min:1'],
            'item_ref' => ['nullable', 'string', 'max:50'],
            'notes' => ['nullable', 'string'],
            'accessories' => ['nullable', 'array'],
            'accessories.*' => ['array'],
            'accessories.*.accessory_id' => ['required', 'integer'],
            'accessories.*.quantity' => ['nullable', 'integer', 'min:1'],
        ];
    }

    public function toInput(): SpecificationItemInput
    {
        return SpecificationItemInput::fromArray($this->validated());
    }
}


