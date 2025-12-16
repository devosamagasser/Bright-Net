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
            'quantity' => ['nullable', 'integer', 'min:1'],
            'item_ref' => ['nullable', 'string', 'max:50']
        ];
    }

    public function toInput(): QuotationAccessoryInput
    {
        return QuotationAccessoryInput::fromArray($this->validated());
    }
}
