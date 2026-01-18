<?php

namespace App\Modules\Quotations\Presentation\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Modules\Quotations\Application\DTOs\QuotationInput;

class UpdateQuotationFlagsRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'discount_applied' => ['required', 'boolean'],
            'vat_applied' => ['required', 'boolean'],
        ];
    }

    public function toInput(): QuotationInput
    {
        return QuotationInput::fromArray($this->validated());
    }
}



