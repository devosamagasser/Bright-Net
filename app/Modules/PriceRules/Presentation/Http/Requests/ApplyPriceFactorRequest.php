<?php

namespace App\Modules\PriceRules\Presentation\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ApplyPriceFactorRequest extends FormRequest
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
            'product_ids' => ['required_without:brand_id', 'array', 'min:1'],
            'product_ids.*' => ['integer'],

            'brand_id' => ['nullable', 'integer'],

            'factor' => ['required', 'numeric', 'min:0.0001'],
            'notes' => ['nullable', 'string', 'max:1000'],

            'category_id' => ['nullable', 'integer'],
            'subcategory_id' => ['nullable', 'integer'],
            'family_id' => ['nullable', 'integer'],
        ];
    }
}

