<?php

namespace App\Modules\DataSheets\Presentation\Http\Requests;

use App\Modules\DataSheets\Domain\Models\DataField;

class UpdateDataTemplateRequest extends StoreDataTemplateRequest
{
    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        $rules = parent::rules();
        $rules['fields.*.id'] = ['sometimes', 'integer', 'exists:data_fields,id'];

        return $rules;
    }

    public function withValidator($validator): void
    {
        parent::withValidator($validator);

        $validator->after(function ($validator) {
            $fields = $this->input('fields', []);
            $templateId = (int) $this->route('dataTemplate');

            if (! $templateId || empty($fields)) {
                return;
            }

            $validIds = DataField::query()
                ->where('data_template_id', $templateId)
                ->pluck('id')
                ->all();

            foreach ($fields as $index => $field) {
                $fieldId = $field['id'] ?? null;

                if ($fieldId && ! in_array((int) $fieldId, $validIds, true)) {
                    $validator->errors()->add(
                        "fields.$index.id",
                        trans('validation.exists', ['attribute' => 'id'])
                    );
                }
            }
        });
    }
}
