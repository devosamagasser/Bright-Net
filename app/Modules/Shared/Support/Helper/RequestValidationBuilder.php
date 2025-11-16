<?php

namespace App\Modules\Shared\Support\Helper;

use Illuminate\Validation\Rule;
use App\Modules\DataSheets\Domain\Models\DataTemplate;
use App\Modules\DataSheets\Domain\ValueObjects\DataFieldType;
use App\Modules\DataSheets\Domain\ValueObjects\DataTemplateType;
use App\Modules\DataSheets\Domain\Repositories\DataTemplateRepositoryInterface;
use Request;

class RequestValidationBuilder
{
    public static function build(int $subcategoryId, DataTemplateType $dataTemplateType): array
    {
        return match($dataTemplateType) {
            DataTemplateType::FAMILY => self::buildFamilyRules($subcategoryId, $dataTemplateType),
            DataTemplateType::PRODUCT => self::buildTemplateFieldsValidation($subcategoryId, $dataTemplateType),
        };
    }

    private static function buildFamilyRules(int $subcategoryId, DataTemplateType $dataTemplateType)
    {
        $dataTemplateExists = DataTemplate::where('subcategory_id', $subcategoryId)
                                ->where('type', DataTemplateType::FAMILY)
                                ->exists();

        if(! $dataTemplateExists) {
            return [];
        }

        return self::buildTemplateFieldsValidation($subcategoryId, $dataTemplateType);
    }


    public static function buildTemplateFieldsValidation($subcategoryId, DataTemplateType $dataTemplateType): array
    {
        $rules = [
            'values' => ['required', 'array']
        ];

        $template = DataTemplate::with('fields')->where('subcategory_id', $subcategoryId)
                        ->where('type', $dataTemplateType)
                        ->first();

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
                $allowed = $field->options;

                if ($type === DataFieldType::SELECT) {
                    $ruleSet[] = Rule::in($allowed);
                } else {
                    $rules['values.' . $fieldKey . '.*'] = [Rule::in($allowed)];
                }
            }

            $rules['values.' . $fieldKey] = $ruleSet;
        }

        return $rules;
    }

}
