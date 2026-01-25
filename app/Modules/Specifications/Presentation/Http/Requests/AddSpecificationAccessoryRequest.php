<?php

namespace App\Modules\Specifications\Presentation\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Modules\Specifications\Application\DTOs\SpecificationAccessoryInput;

class AddSpecificationAccessoryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'accessory_id' => ['required', 'integer'],
            'quantity' => ['nullable', 'integer', 'min:1'],
            'item_ref' => ['nullable', 'string', 'max:50'],
            'position' => ['nullable', 'integer', 'min:1'],
            'notes' => ['nullable', 'string'],
        ];
    }

    public function toInput(): SpecificationAccessoryInput
    {
        return SpecificationAccessoryInput::fromArray($this->validated());
    }
}


