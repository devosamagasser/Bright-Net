<?php

namespace App\Modules\PriceRules\Presentation\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateCurrencyTransformFactorRequest extends FormRequest
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
            'factor' => ['required', 'numeric', 'min:0.0001'],
        ];
    }
}

