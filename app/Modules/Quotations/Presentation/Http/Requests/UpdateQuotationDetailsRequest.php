<?php

namespace App\Modules\Quotations\Presentation\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Modules\Quotations\Application\DTOs\QuotationInput;

class UpdateQuotationDetailsRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'general_notes' => ['sometimes', 'nullable', 'array'],
            'warranty' => ['sometimes', 'nullable', 'array'],
            'warranty_and_payments' => ['sometimes', 'nullable', 'array'],
        ];
    }

    public function toInput(): QuotationInput
    {
        return QuotationInput::fromArray($this->validated());
    }
}



