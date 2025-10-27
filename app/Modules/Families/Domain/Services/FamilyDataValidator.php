<?php

namespace App\Modules\Families\Domain\Services;

use Carbon\Carbon;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use App\Modules\DataSheets\Domain\Models\DataTemplate;
use App\Modules\DataSheets\Domain\ValueObjects\DataFieldType;

class FamilyDataValidator
{
    /**
     * @param  array<string, mixed>  $values
     * @return array<string, mixed>
     *
     * @throws ValidationException
     */
    public function validate(DataTemplate $template, array $values): array
    {
        $rules = [];
        $attributes = [];

        foreach ($template->fields as $field) {
            $slug = $field->slug;
            $key = "values.$slug";
            $fieldRules = $field->is_required ? ['required'] : ['nullable'];

            $fieldType = $field->type instanceof DataFieldType
                ? $field->type
                : DataFieldType::from($field->type);

            switch ($fieldType) {
                case DataFieldType::TEXT:
                    $fieldRules[] = 'string';
                    break;
                case DataFieldType::NUMBER:
                    $fieldRules[] = 'numeric';
                    break;
                case DataFieldType::BOOLEAN:
                    $fieldRules[] = 'boolean';
                    break;
                case DataFieldType::DATE:
                    $fieldRules[] = 'date';
                    break;
                case DataFieldType::SELECT:
                    $fieldRules[] = 'string';
                    $fieldRules[] = Rule::in($field->options ?? []);
                    break;
                case DataFieldType::MULTISELECT:
                    $fieldRules[] = 'array';
                    $itemRule = [Rule::in($field->options ?? [])];
                    $rules["values.$slug.*"] = $field->is_required ? array_merge(['required', 'string'], $itemRule) : array_merge(['nullable', 'string'], $itemRule);
                    break;
            }

            $rules[$key] = $fieldRules;
            $attributes[$key] = $field->label ?? $slug;
            $attributes["values.$slug.*"] = $field->label ?? $slug;
        }

        $validator = Validator::make(
            ['values' => $values],
            $rules,
            [],
            $attributes
        );

        if ($validator->fails()) {
            throw new ValidationException($validator);
        }

        $validated = $validator->validated();
        $validatedValues = Arr::get($validated, 'values', []);
        $normalized = [];

        foreach ($template->fields as $field) {
            $slug = $field->slug;
            if (! array_key_exists($slug, $validatedValues)) {
                continue;
            }

            $value = $validatedValues[$slug];

            if ($value === null) {
                $normalized[$slug] = null;
                continue;
            }

            $fieldType = $field->type instanceof DataFieldType
                ? $field->type
                : DataFieldType::from($field->type);

            switch ($fieldType) {
                case DataFieldType::NUMBER:
                    $normalized[$slug] = is_numeric($value) ? $value + 0 : $value;
                    break;
                case DataFieldType::BOOLEAN:
                    $normalized[$slug] = filter_var($value, FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE);
                    break;
                case DataFieldType::DATE:
                    $normalized[$slug] = Carbon::parse($value)->toDateString();
                    break;
                case DataFieldType::MULTISELECT:
                    $normalized[$slug] = collect($value ?? [])->filter(static fn ($item) => $item !== null)->values()->all();
                    break;
                default:
                    $normalized[$slug] = $value;
                    break;
            }
        }

        return $normalized;
    }
}
