<?php

namespace App\Modules\Products\Presentation\Http\Requests;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;
use App\Modules\Families\Domain\Models\Family;
use App\Modules\Shared\Support\Helper\RequestValidationBuilder;
use App\Modules\DataSheets\Domain\ValueObjects\DataTemplateType;
use App\Modules\Products\Domain\ValueObjects\{AccessoryType, PriceCurrency, DeliveryTimeUnit};
use Illuminate\Validation\Rules\Enum;

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
            'stock' => ['nullable', 'integer', 'min:0'],
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
            'prices.*.price' => ['nullable', 'numeric', 'min:0'],
            'prices.*.from' => ['required_with:prices.*.price', 'integer', 'min:0'],
            'prices.*.to' => ['required_with:prices.*.price', 'integer', 'min:0'],
            'prices.*.currency' => ['required_with:prices.*.price', 'string', new Enum(PriceCurrency::class)],
            'prices.*.delivery_time_unit' => ['required_with:prices.*.price', 'string', new Enum(DeliveryTimeUnit::class)],
            'prices.*.delivery_time_value' => ['required_with:prices.*.price', 'string'],
            'prices.*.vat_status' => ['required_with:prices.*.price', 'boolean'],

            'accessories' => ['nullable', 'array'],
            'accessories.*.code' => ['required', 'string'],
            'accessories.*.type' => ['required', 'string', new Enum(AccessoryType::class)],
            'accessories.*.quantity' => ['required', 'integer', 'min:1'],

            'colors' => ['nullable', 'array'],
            'colors.*' => ['integer', 'exists:colors,id'],

            // media \\
            'gallery' => ['nullable', 'array'],
            'gallery.*' => ['file'],
            'old_gallery' => ['nullable', 'array'],
            'old_gallery.*' => ['string', 'url'],

            'quotation_image' => ['nullable', 'file'],
            'old_quotation_image.*' => ['string', 'url'],

            'documents' => ['nullable', 'array'],
            'documents.*' => ['file'],
            'old_documents' => ['nullable', 'array'],
            'old_documents.*' => ['string', 'url'],

            'dimensions' => ['nullable', 'array'],
            'dimensions.*' => ['file'],
            'old_dimensions' => ['nullable', 'array'],
            'old_dimensions.*' => ['string', 'url'],
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
