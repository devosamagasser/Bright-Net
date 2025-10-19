<?php

namespace App\Modules\SolutionsCatalog\Presentation\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateSolutionRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'translations.en.name' => 'required|string|min:2|max:255',
            'translations.ar.name' => 'nullable|string|min:2|max:255',
        ];
    }
}
