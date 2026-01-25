<?php

namespace App\Modules\PriceRules\Presentation\Http\Requests;

use App\Modules\PriceRules\Domain\ValueObjects\PriceCurrency;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;

class StoreCurrencyTransformFactorRequest extends FormRequest
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
            'from' => ['required', 'string', new Enum(PriceCurrency::class)],
            'to' => ['required', 'string', new Enum(PriceCurrency::class), 'different:from'],
            'factor' => ['required', 'numeric', 'min:0.0001'],
        ];
    }
}

