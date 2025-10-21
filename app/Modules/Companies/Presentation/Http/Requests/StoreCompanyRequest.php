<?php

namespace App\Modules\Companies\Presentation\Http\Requests;

use Illuminate\Validation\Rule;
use App\Modules\Companies\Domain\ValueObjects\CompanyType;

class StoreCompanyRequest extends CompanyRequest
{
    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'type' => ['required', Rule::enum(CompanyType::class)],
            'logo' => ['nullable', 'image', 'max:2048'],
        ];
    }
}
