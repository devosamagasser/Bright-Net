<?php

namespace App\Modules\Brands\Presentation\Http\Requests;

class StoreBrandRequest extends BrandRequest
{
    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return array_merge([
            'name' => ['required', 'string', 'max:255'],
            'region_id' => ['required', 'integer', 'exists:regions,id'],
            'cover' => ['required', 'image', 'max:2048'], // Assuming cover is an image file
        ], $this->relationRules());
    }
}
