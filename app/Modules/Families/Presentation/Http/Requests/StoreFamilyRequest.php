<?php

namespace App\Modules\Families\Presentation\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Modules\Shared\Support\Helper\RequestValidationBuilder;
use App\Modules\DataSheets\Domain\ValueObjects\DataTemplateType;

class StoreFamilyRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'subcategory_id' => ['required', 'integer', 'exists:subcategories,id'],
            'supplier_department_id' => ['required', 'integer', 'exists:supplier_departments,id'],
            'name' => ['required', 'string', 'min:1', 'max:255'],
            'image' => ['nullable', 'image'],
            'translations' => ['required', 'array', 'min:1'],
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
