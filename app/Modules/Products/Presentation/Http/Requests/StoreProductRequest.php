<?php

namespace App\Modules\Products\Presentation\Http\Requests;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;
use App\Modules\Families\Domain\Models\Family;
use App\Modules\Shared\Support\Helper\RequestValidationBuilder;
use App\Modules\DataSheets\Domain\ValueObjects\DataTemplateType;
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

        return [
            'family_id' => ['required', 'integer', 'exists:families,id'],
            'code' => [
                'required',
                'string',
                'max:255',
                Rule::unique('products', 'code')->where(fn ($query) => $query->where('family_id', $familyId)),
            ],
            'stock' => ['required', 'integer', 'min:0'],
            'disclaimer' => ['nullable', 'string'],
            'color' => ['nullable', 'string'],
            'style'=> ['nullable', 'string'],
            'manufacturer' => ['nullable', 'string'],
            'application' => ['nullable', 'string'],
            'origin' => ['nullable', 'string'],

            'translations' => ['required', 'array', 'min:1'],
            'translations.ar.name' => ['nullable', 'string', 'min:1', 'max:255'],
            'translations.en.name' => ['required', 'string', 'min:1', 'max:255'],
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

            'colors' => ['nullable', 'array'],
            'colors.*' => ['integer', 'exists:colors,id'],

            'old_gallery' => ['nullable', 'array'],
            'old_gallery.*' => ['string', 'url'],
            'gallery' => ['nullable', 'array'],
            'gallery.*' => ['file'],

            'documents' => ['nullable', 'array'],
            'documents.*' => ['file'],

            'dimensions' => ['nullable', 'array'],
            'dimensions.*' => ['file'],
        ];

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
                DataTemplateType::PRODUCT
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
