<?php

namespace App\Modules\Quotations\Presentation\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Modules\Quotations\Application\DTOs\QuotationAccessoryInput;
use App\Modules\Products\Domain\ValueObjects\{AccessoryType, DeliveryTimeUnit, PriceCurrency};

class AddQuotationAccessoryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'accessory_id' => ['required', 'integer', 'exists:products,id'],
            'accessory_type' => ['required', Rule::in([AccessoryType::OPTIONAL->value])],
            'quantity' => ['nullable', 'integer', 'min:1'],
            // 'item_ref' => ['nullable', 'string', 'max:50'],
            // 'position' => ['nullable', 'integer', 'min:0'],
            // 'notes' => ['nullable', 'string'],
            // 'price' => ['nullable', 'numeric', 'min:0'],
            // 'discount' => ['nullable', 'numeric', 'min:0', 'max:100'],
            // 'currency' => ['nullable', Rule::in(PriceCurrency::values())],
            // 'delivery_time_unit' => ['nullable', Rule::in(DeliveryTimeUnit::values())],
            // 'delivery_time_value' => ['nullable', 'string', 'max:50'],
            // 'vat_included' => ['nullable', 'boolean'],
        ];
    }

    public function toInput(): QuotationAccessoryInput
    {
        return QuotationAccessoryInput::fromArray($this->validated());
    }
}
