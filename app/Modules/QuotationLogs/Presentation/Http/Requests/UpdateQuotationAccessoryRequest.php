<?php

namespace App\Modules\Quotations\Presentation\Http\Requests;

use App\Modules\PriceRules\Domain\ValueObjects\PriceCurrency;
use App\Modules\Quotations\Application\DTOs\QuotationAccessoryUpdateInput;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateQuotationAccessoryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'quantity' => ['sometimes', 'nullable', 'integer', 'min:1'],
            'item_ref' => ['sometimes', 'nullable', 'string', 'max:50'],
            'notes' => ['sometimes', 'nullable', 'string'],
            'discount' => ['sometimes', 'nullable', 'numeric', 'min:0', 'max:100'],
            'currency' => ['sometimes', 'nullable', Rule::in(PriceCurrency::values())],
        ];
    }

    public function toInput(): QuotationAccessoryUpdateInput
    {
        return QuotationAccessoryUpdateInput::fromArray($this->validated());
    }
}
