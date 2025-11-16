<?php

namespace App\Modules\Shared\Support\Helper;

use Illuminate\Validation\Rule;
use App\Modules\DataSheets\Domain\Models\{DataField, DataTemplate};
use App\Modules\DataSheets\Domain\Models\DependedField;
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

        $template = DataTemplate::with('fields.dependencies.dependsOnField')->where('subcategory_id', $subcategoryId)
                        ->where('type', $dataTemplateType)
                        ->first();

        if (! $template || ! $template->fields) {
            return [];
        }

        $submittedValues = Request::input('values', []);

        foreach ($template->fields as $field) {
            $type = $field->type;
            $fieldKey = $field->name;

            $ruleSet = [
                'nullable',
                $type->validation(),
                Rule::requiredIf(function () use ($field, $submittedValues) {
                    return self::fieldShouldBeRequired($field, (array) $submittedValues);
                }),
            ];

            if ($type->requiresOptions() && ! empty($field->options)) {
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

    private static function fieldShouldBeRequired(DataField $field, array $values): bool
    {
        if (! $field->is_required) {
            return false;
        }

        if ($field->dependencies->isEmpty()) {
            return true;
        }

        foreach ($field->dependencies as $dependency) {
            if (! self::dependencySatisfied($dependency, $values)) {
                return false;
            }
        }

        return true;
    }

    private static function dependencySatisfied(DependedField $dependency, array $values): bool
    {
        $dependsOnField = $dependency->dependsOnField;

        if (! $dependsOnField) {
            return false;
        }

        $fieldName = $dependsOnField->name;
        $submitted = $values[$fieldName] ?? null;
        $expectedValues = $dependency->values ?? [];

        if ($submitted === null || $submitted === '') {
            return false;
        }

        if (is_array($submitted)) {
            return ! empty(array_intersect($expectedValues, $submitted));
        }

        return in_array($submitted, $expectedValues, true);
    }
}
