<?php

namespace App\Modules\Products\Presentation\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Modules\Shared\Support\Helper\RequestValidationBuilder;
use App\Modules\Products\Domain\Models\Product;
use App\Modules\Products\Domain\ValueObjects\{AccessoryType, PriceCurrency, DeliveryTimeUnit};

class UpdateProductRequest extends FormRequest
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
        $product = $this->route('product');
        $productId = $product instanceof Product ? (int) $product->getKey() : null;
        $familyId = $this->determineFamilyId($product);

        $rules = [
            'family_id' => ['sometimes', 'integer', 'exists:families,id'],
            'data_template_id' => ['sometimes', 'integer', 'exists:data_templates,id'],
            'code' => [
                'sometimes',
                'string',
                'max:255',
                Rule::unique('products', 'code')
                    ->ignore($productId)
                    ->where(fn ($query) => $familyId ? $query->where('family_id', $familyId) : $query),
            ],
            'stock' => ['sometimes', 'integer', 'min:0'],
            'disclaimer' => ['nullable', 'string'],
            'translations' => ['sometimes', 'array'],
            'translations.*.name' => ['required_with:translations', 'string', 'min:1', 'max:255'],
            'translations.*.description' => ['nullable', 'string'],
            'values' => ['sometimes', 'array'],
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

        $templateId = $this->determineTemplateId($product);
        $shouldValidateValues = $this->has('values') || $this->filled('values') || $this->has('data_template_id');

        if ($templateId !== null && $shouldValidateValues) {
            $rules = array_merge(
                $rules,
                RequestValidationBuilder::build($templateId)
            );
        }

        return $rules;
    }

    private function determineTemplateId(?Product $product): ?int
    {
        if ($this->filled('data_template_id')) {
            return (int) $this->input('data_template_id');
        }

        return $product?->data_template_id ? (int) $product->data_template_id : null;
    }

    private function determineFamilyId(?Product $product): ?int
    {
        if ($this->filled('family_id')) {
            return (int) $this->input('family_id');
        }

        return $product?->family_id ? (int) $product->family_id : null;
    }
}
