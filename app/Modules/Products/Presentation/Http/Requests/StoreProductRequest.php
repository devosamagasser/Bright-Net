<?php

namespace App\Modules\Products\Presentation\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Modules\Shared\Support\Helper\RequestValidationBuilder;
use App\Modules\Products\Domain\ValueObjects\{AccessoryType, PriceCurrency, DeliveryTimeUnit};

class StoreProductRequest extends FormRequest
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
        $familyId = (int) $this->input('family_id');

        $rules = [
            'family_id' => ['required', 'integer', 'exists:families,id'],
            'data_template_id' => ['required', 'integer', 'exists:data_templates,id'],
            'code' => [
                'required',
                'string',
                'max:255',
                Rule::unique('products', 'code')->where(fn ($query) => $query->where('family_id', $familyId)),
            ],
            'stock' => ['required', 'integer', 'min:0'],
            'disclaimer' => ['nullable', 'string'],
            'translations' => ['required', 'array', 'min:1'],
            'translations.*.name' => ['required', 'string', 'min:1', 'max:255'],
            'translations.*.description' => ['nullable', 'string'],
            'values' => ['required', 'array'],
            'prices' => ['sometimes', 'array'],
            'prices.*.price' => ['required', 'numeric', 'min:0'],
            'prices.*.from' => ['required', 'integer', 'min:0'],
            'prices.*.to' => ['required', 'integer', 'min:0'],
            'prices.*.currency' => ['required', 'string', Rule::in(PriceCurrency::values())],
            'prices.*.delivery_time_unit' => ['required', 'string', Rule::in(DeliveryTimeUnit::values())],
            'prices.*.delivery_time_value' => ['required', 'string'],
            'prices.*.vat_status' => ['required', 'boolean'],
            'accessories' => ['sometimes', 'array'],
            'accessories.*.code' => ['required', 'string'],
            'accessories.*.type' => ['required', 'string', Rule::in(AccessoryType::values())],
            'colors' => ['sometimes', 'array'],
            'colors.*' => ['integer', 'exists:colors,id'],
            'gallery' => ['sometimes', 'array'],
            'gallery.*' => ['file'],
            'documents' => ['sometimes', 'array'],
            'documents.*' => ['file'],
            'consultant_approvals' => ['sometimes', 'array'],
            'consultant_approvals.*' => ['file'],
        ];

        $templateId = (int) $this->input('data_template_id');

        return array_merge(
            $rules,
            RequestValidationBuilder::build($templateId)
        );
    }
}
