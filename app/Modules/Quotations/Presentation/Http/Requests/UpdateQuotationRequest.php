<?php

namespace App\Modules\Quotations\Presentation\Http\Requests;

use App\Modules\PriceRules\Domain\ValueObjects\PriceCurrency;
use App\Modules\Quotations\Application\DTOs\QuotationInput;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateQuotationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'reference' => ['sometimes', 'nullable', 'string', 'max:100'],
            'title' => ['sometimes', 'nullable', 'string', 'max:150'],
            'company_id' => ['sometimes', 'nullable', 'integer', 'exists:companies,id'],
            'valid_until' => ['sometimes', 'nullable', 'date'],
            'notes' => ['sometimes', 'nullable', 'string'],
            'currency' => ['sometimes', 'nullable', Rule::in(PriceCurrency::values())],
            'general_notes' => ['sometimes', 'nullable', 'array'],
            'warranty' => ['sometimes', 'nullable', 'array'],
            'warranty_and_payments' => ['sometimes', 'nullable', 'array'],
            'discount_applied' => ['required', 'boolean'],
            'vat_applied' => ['required', 'boolean'],
        ];
    }

    public function toInput(): QuotationInput
    {
        return QuotationInput::fromArray($this->validated());
    }
}
