<?php

namespace App\Modules\PriceRules\Presentation\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class FlattenPriceFactorsHistoryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'factor_id' => ['required', 'integer', 'exists:price_factors,id'],
        ];
    }
}

