<?php

namespace App\Modules\Products\Presentation\Http\Requests;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;
use App\Modules\Families\Domain\Models\Family;
use App\Modules\Products\Domain\Models\Product;
use App\Modules\Shared\Support\Helper\RequestValidationBuilder;
use App\Modules\DataSheets\Domain\ValueObjects\DataTemplateType;
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

        return [
            'family_id' => ['required', 'integer', 'exists:families,id'],
            'code' => [
                'required',
                'string',
                'max:255',
                Rule::unique('products', 'code')
                    ->ignore($productId)
                    ->where(fn ($query) => $familyId ? $query->where('family_id', $familyId) : $query),
            ],
            'stock' => ['required', 'integer', 'min:0'],
            'disclaimer' => ['nullable', 'string'],
            'color' => ['nullable', 'string'],
            'style'=> ['nullable', 'string'],
            'manufacturer' => ['nullable', 'string'],
            'application' => ['nullable', 'string'],
            'origin' => ['nullable', 'string'],

            'translations' => ['required', 'array'],
            'translations.*.name' => ['required', 'string', 'min:1', 'max:255'],
            'translations.*.description' => ['nullable', 'string'],

            'prices' => ['nullable', 'array'],
            'prices.*.price' => ['required', 'numeric', 'min:0'],
            'prices.*.from' => ['required', 'integer', 'min:0'],
            'prices.*.to' => ['required', 'integer', 'min:0'],
            'prices.*.currency' => ['required', 'string', Rule::in(PriceCurrency::values())],
            'prices.*.delivery_time_unit' => ['required', 'string', Rule::in(DeliveryTimeUnit::values())],
            'prices.*.delivery_time_value' => ['required', 'string'],
            'prices.*.vat_status' => ['required', 'boolean'],

            'accessories' => ['nullable', 'array'],
            'accessories.*.code' => ['required', 'string'],
            'accessories.*.type' => ['required', 'string', Rule::in(AccessoryType::values())],
            'accessories.*.quantity' => ['required', 'integer', 'min:1'],

            'oldGallery' => ['nullable', 'array'],
            'oldGallery.*' => ['file'],
            'gallery' => ['nullable', 'array'],
            'gallery.*' => ['file'],

            'documents' => ['nullable', 'array'],
            'documents.*' => ['file'],

            'dimensions' => ['nullable', 'array'],
            'dimensions.*' => ['file'],
        ];
    }

    private function determineFamilyId(?Product $product): ?int
    {
        if ($this->filled('family_id')) {
            return (int) $this->input('family_id');
        }

        return $product?->family_id ? (int) $product->family_id : null;
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {

            $errors = $validator->errors();

            if ($errors->any()) {
                return;
            }


            $subcategoryId = Family::find((int) $this->input('family_id'))->subcategory_id;
            $dynamicRules = RequestValidationBuilder::build(
                $subcategoryId,
                DataTemplateType::FAMILY
            );

            if (empty($dynamicRules)) {
                return;
            }

            $dynamicValidator = \Validator::make($this->all(), $dynamicRules);

            if ($dynamicValidator->fails()) {
                foreach ($dynamicValidator->errors()->messages() as $key => $messages) {
                    foreach ($messages as $message) {
                        $validator->errors()->add($key, $message);
                    }
                }
            }
        });
    }

}
