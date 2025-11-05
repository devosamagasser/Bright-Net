<?php

namespace App\Modules\Quotations\Presentation\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Modules\Quotations\Application\DTOs\QuotationProductInput;
use App\Modules\Products\Domain\ValueObjects\{AccessoryType, DeliveryTimeUnit, PriceCurrency};

class AddQuotationProductRequest extends FormRequest
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
            'position' => ['nullable', 'integer', 'min:0'],
            'notes' => ['nullable', 'string'],
            'price' => ['nullable', 'numeric', 'min:0'],
            'discount' => ['nullable', 'numeric', 'min:0', 'max:100'],
            'currency' => ['nullable', Rule::in(PriceCurrency::values())],
            'delivery_time_unit' => ['nullable', Rule::in(DeliveryTimeUnit::values())],
            'delivery_time_value' => ['nullable', 'string', 'max:50'],
            'vat_included' => ['nullable', 'boolean'],
            'accessories' => ['nullable', 'array'],
            'accessories.*' => ['array'],
            'accessories.*.accessory_id' => ['required', 'integer', 'exists:products,id'],
            'accessories.*.accessory_type' => ['nullable', Rule::in([AccessoryType::NEEDED->value, AccessoryType::OPTIONAL->value])],
            'accessories.*.quantity' => ['nullable', 'integer', 'min:1'],
            'accessories.*.item_ref' => ['nullable', 'string', 'max:50'],
            'accessories.*.position' => ['nullable', 'integer', 'min:0'],
            'accessories.*.notes' => ['nullable', 'string'],
            'accessories.*.price' => ['nullable', 'numeric', 'min:0'],
            'accessories.*.discount' => ['nullable', 'numeric', 'min:0', 'max:100'],
            'accessories.*.currency' => ['nullable', Rule::in(PriceCurrency::values())],
            'accessories.*.delivery_time_unit' => ['nullable', Rule::in(DeliveryTimeUnit::values())],
            'accessories.*.delivery_time_value' => ['nullable', 'string', 'max:50'],
            'accessories.*.vat_included' => ['nullable', 'boolean'],
        ];
    }

    public function toInput(): QuotationProductInput
    {
        return QuotationProductInput::fromArray($this->validated());
    }
}
