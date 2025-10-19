<?php

namespace App\Modules\Taxonomy\Presentation\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateColorRequest extends FormRequest
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
            'hex_code' => ['required', 'string', 'regex:/^#[0-9A-Fa-f]{6}$/'],
            'translations.en.name' => ['required', 'string', 'min:2', 'max:255'],
            'translations.ar.name' => ['nullable', 'string', 'min:2', 'max:255'],
        ];
    }
}
