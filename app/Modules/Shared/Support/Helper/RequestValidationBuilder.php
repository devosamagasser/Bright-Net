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
                match ($type) {
                    DataFieldType::SELECT => $ruleSet[] = self::rawSelectValidation($field->options),
                    DataFieldType::MULTISELECT => $rules['values.' . $fieldKey . '.*'] = self::multiSelectValidation($field->options),
                    DataFieldType::GROUPEDSELECT => null,
                    default => throw new \InvalidArgumentException("Unsupported field type for options validation: {$type->value}"),
                };
            }

            $rules['values.' . $fieldKey] = $ruleSet;
        }

        return $rules;
    }

    private static function rawSelectValidation($options)
    {
        if(is_array($options[0])){
            foreach($options as $option){
                $optionsValues[] = $option['value'] ?? ;
            }
        }else{
            $optionsValues = $options;
        }
        return Rule::in($optionsValues);
    }

    private static function multiSelectValidation($options)
    {
        return [Rule::in($options)];
    }



}
