<?php

namespace App\Modules\Families\Presentation\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Modules\Families\Domain\Models\Family;
use App\Modules\Shared\Support\Helper\RequestValidationBuilder;
use App\Modules\DataSheets\Domain\ValueObjects\DataTemplateType;

class UpdateFamilyRequest extends FormRequest
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
        return [
            'subcategory_id' => ['sometimes', 'integer', 'exists:subcategories,id'],
            'name' => ['required', 'string', 'min:1', 'max:255'],
            'image' => ['nullable', 'image'],
            'image_url' => ['nullable', 'url'],
            'translations' => ['sometimes', 'array'],
            'translations.*.description' => ['nullable', 'string'],
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {

            $errors = $validator->errors();

            if ($errors->any()) {
                return;
            }


            $subcategoryId = (int) $this->input('subcategory_id');
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
