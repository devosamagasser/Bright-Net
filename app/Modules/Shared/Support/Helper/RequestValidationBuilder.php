<?php

namespace App\Modules\Shared\Support\Helper;

use Illuminate\Validation\Rule;
use App\Modules\DataSheets\Domain\Models\DataTemplate;
use App\Modules\DataSheets\Domain\ValueObjects\DataFieldType;
use App\Modules\DataSheets\Domain\ValueObjects\DataTemplateType;
use Request;

class RequestValidationBuilder
{
    public static function build(int $subcategoryId, DataTemplateType $dataTemplateType): array
    {
        $template = DataTemplate::with('fields')->where('subcategory_id', $subcategoryId)
                        ->where('type', $dataTemplateType)
                        ->first();

        if(is_null($template)) {
            return [];
        }

        $rules = [
            'values' => ['required', 'array']
        ];


        if (! $template->fields) {
            return [];
        }

        foreach ($template->fields as $field) {
            $type = $field->type;
            $fieldKey = $field->name;

            $ruleSet = [
                $field->is_required ? 'required' : 'nullable',
                $type->validation(),
            ];

            if ($type->requiresOptions() && !empty($field->options)) {
                $allowed = $field->options['values'] ?? $field->options;

                if ($type === DataFieldType::SELECT) {
                    $ruleSet[] = Rule::in($allowed);
                } else if($type === DataFieldType::MULTISELECT) {
                    $rules['values.' . $fieldKey . '.*'] = [Rule::in($allowed)];
                }
            }

            $rules['values.' . $fieldKey] = $ruleSet;
        }

        return $rules;
    }

}
