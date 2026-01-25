<?php

namespace App\Modules\Quotations\Presentation\Http\Requests;

use App\Modules\PriceRules\Domain\ValueObjects\PriceCurrency;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Modules\Quotations\Application\DTOs\QuotationAccessoryInput;
use App\Modules\Products\Domain\ValueObjects\{AccessoryType, DeliveryTimeUnit};

class AddQuotationAccessoryRequest extends FormRequest
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
            'item_ref' => ['nullable', 'string', 'max:50']
        ];
    }

    public function toInput(): QuotationAccessoryInput
    {
        return QuotationAccessoryInput::fromArray($this->validated());
    }
}
