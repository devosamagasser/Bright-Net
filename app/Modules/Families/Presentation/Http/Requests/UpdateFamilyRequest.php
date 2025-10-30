<?php

namespace App\Modules\Families\Presentation\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Modules\Shared\Support\Helper\RequestValidationBuilder;
use App\Modules\Families\Domain\Models\Family;

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
        $keys = [
            'subcategory_id' => ['sometimes', 'integer', 'exists:subcategories,id'],
            'data_template_id' => ['sometimes', 'integer', 'exists:data_templates,id'],
            'name' => ['required', 'string', 'min:1', 'max:255'],
            'image' => ['nullable', 'image'],
            'translations' => ['sometimes', 'array'],
            'translations.*.description' => ['nullable', 'string'],
            'values' => ['sometimes', 'array'],
        ];

        $templateId = $this->determineTemplateId();
        $shouldValidateValues = $this->has('values') || $this->filled('values') || $this->has('data_template_id');

        if ($templateId !== null && $shouldValidateValues) {
            $keys = array_merge(
                $keys,
                RequestValidationBuilder::build($templateId)
            );
        }

        return $keys;
    }

    private function determineTemplateId(): ?int
    {
        if ($this->filled('data_template_id')) {
            return (int) $this->input('data_template_id');
        }

        $family = $this->route('family');

        return $family instanceof Family
            ? (int) $family->data_template_id
            : null;
    }
}
