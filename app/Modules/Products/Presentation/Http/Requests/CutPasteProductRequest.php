<?php

namespace App\Modules\Products\Presentation\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CutPasteProductRequest extends FormRequest
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
            'family_id' => ['required', 'integer', 'exists:families,id'],
        ];
    }

}
