<?php

namespace App\Modules\Quotations\Presentation\Http\Requests;

use App\Modules\Quotations\Application\DTOs\QuotationProductInput;

class ReplaceQuotationProductRequest extends AddQuotationProductRequest
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
            'accessories' => ['nullable', 'array'],
            'accessories.*' => ['array'],
            'accessories.*.accessory_id' => ['required', 'integer', 'exists:products,id'],
            'accessories.*.quantity' => ['nullable', 'integer', 'min:1'],
        ];
    }

    public function toInput(): QuotationProductInput
    {
        return QuotationProductInput::fromArray($this->validated());
    }
}
