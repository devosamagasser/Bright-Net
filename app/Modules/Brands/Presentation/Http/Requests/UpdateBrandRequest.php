<?php

namespace App\Modules\Brands\Presentation\Http\Requests;

class UpdateBrandRequest extends BrandRequest
{
    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return array_merge([
            'name' => ['required', 'string', 'max:255'],
            'region_id' => ['required', 'integer', 'exists:regions,id'],
        ], $this->relationRules());
    }
}
