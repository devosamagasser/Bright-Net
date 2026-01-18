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
            'product_id' => ['required', 'integer'],
            'quantity' => ['required', 'integer', 'min:1'],
            'item_ref' => ['nullable', 'string', 'max:50'],
            'accessories' => ['nullable', 'array'],
            'accessories.*' => ['array'],
            'accessories.*.accessory_id' => ['required', 'integer'],
//            'accessories.*.accessory_type' => ['required', Rule::in([AccessoryType::OPTIONAL->value])],
            'accessories.*.quantity' => ['nullable', 'integer', 'min:1'],
        ];
    }

    public function toInput(): QuotationProductInput
    {
        return QuotationProductInput::fromArray($this->validated());
    }
}
