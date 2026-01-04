<?php

namespace App\Modules\Favourites\Presentation\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class AddProductRequest extends FormRequest
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
            'product_id' => ['required', 'integer', Rule::exists('products', 'id')],
        ];
    }

    public function productId(): int
    {
        return (int) $this->validated('product_id');
    }
}

