<?php

namespace App\Modules\Companies\Presentation\Http\Requests\Suppliers;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Arr;
use App\Modules\Companies\Application\DTOs\CompanyInput;
use App\Modules\Companies\Domain\Models\Company;
use App\Modules\Companies\Domain\ValueObjects\CompanyType;

class UpdateSupplierRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'logo' => ['nullable', 'image', 'max:5120'],
            'contact_email' => ['required', 'email', 'max:255'],
            'contact_phone' => ['nullable', 'string', 'max:50'],
            'website' => ['nullable', 'url', 'max:255'],
        ];
    }

    public function toCompanyInput(Company $company): CompanyInput
    {
        if ($company->type !== CompanyType::SUPPLIER) {
            abort(404);
        }

        $validated = $this->validated();

        return CompanyInput::forType(
            CompanyType::SUPPLIER,
            attributes: Arr::only($validated, ['name', 'description']),
            profilePayload: Arr::only($validated, ['contact_email', 'contact_phone', 'website']),
            logo: $this->file('logo'),
        );
    }
}
