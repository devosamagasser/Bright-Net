<?php

namespace App\Modules\Shared\Support\Helper;

use Illuminate\Validation\Rule;
use App\Modules\DataSheets\Domain\ValueObjects\DataFieldType;
use App\Modules\DataSheets\Domain\Repositories\DataTemplateRepositoryInterface;

class RequestValidationBuilder
{
    public static function build(int $templateId): array
    {
        $rules = [];

        $template = resolve(DataTemplateRepositoryInterface::class)->find($templateId);

        if (! $template || ! $template->fields) {
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
