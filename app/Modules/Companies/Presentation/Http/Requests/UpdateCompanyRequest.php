<?php

namespace App\Modules\Companies\Presentation\Http\Requests;

use Illuminate\Validation\Rule;
use App\Modules\Companies\Domain\ValueObjects\CompanyType;

class UpdateCompanyRequest extends CompanyRequest
{
    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'name' => ['sometimes', 'required', 'string', 'max:255'],
            'type' => ['sometimes', 'required', Rule::enum(CompanyType::class)],
            'logo' => ['sometimes', 'nullable', 'image', 'max:2048'],
        ];
    }
}
