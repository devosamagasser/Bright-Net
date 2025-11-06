<?php

namespace App\Modules\Quotations\Presentation\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Modules\Quotations\Application\DTOs\QuotationProductUpdateInput;
use App\Modules\Products\Domain\ValueObjects\{DeliveryTimeUnit, PriceCurrency};

class UpdateQuotationProductRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'item_ref' => ['sometimes', 'nullable', 'string', 'max:50'],
            'position' => ['sometimes', 'nullable', 'integer', 'min:0'],
            'notes' => ['sometimes', 'nullable', 'string'],
            'price' => ['sometimes', 'nullable', 'numeric', 'min:0'],
            'list_price' => ['sometimes', 'nullable', 'numeric', 'min:0'],
            'discount' => ['sometimes', 'nullable', 'numeric', 'min:0', 'max:100'],
            'currency' => ['sometimes', 'nullable', Rule::in(PriceCurrency::values())],
            'quantity' => ['sometimes', 'nullable', 'integer', 'min:1'],
            'delivery_time_unit' => ['sometimes', 'nullable', Rule::in(DeliveryTimeUnit::values())],
            'delivery_time_value' => ['sometimes', 'nullable', 'string', 'max:50'],
            'vat_included' => ['sometimes', 'nullable', 'boolean'],
        ];
    }

    public function toInput(): QuotationProductUpdateInput
    {
        return QuotationProductUpdateInput::fromArray($this->validated());
    }
}
