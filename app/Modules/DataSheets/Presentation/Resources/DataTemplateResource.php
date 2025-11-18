<?php

namespace App\Modules\DataSheets\Presentation\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Modules\DataSheets\Application\DTOs\DataFieldData;

class DataTemplateResource extends JsonResource
{
    /**
     * @param  \Illuminate\Http\Request  $request
     * @return array<string, mixed>
     */
    public function toArray($request): array
    {
        $includeTranslations = $request->is('api/data-templates/*') || $request->method() === 'GET';

        return [
            'id' => $this->attributes['id'] ?? null,
            'subcategory_id' => $this->attributes['subcategory_id'] ?? null,
            'name' => $this->attributes['name'],
            'description' => $this->attributes['description'],
            'type' => $this->attributes['type'] ?? null,
            'translations' => $this->when(
                $request->is('api/data-templates/*') ?? $request->method() === 'GET',
                $includeTranslations,
                $this->translations
            ),
            'fields' => array_map(
                function (DataFieldData $field) use ($request) {
                    return $this->when(
                            $request->is('api/data-templates/*') ?? $request->method() === 'GET',
                            array_merge(
                                $field->attributes, ['translations' => $field->translations],
                            )
                        , $field->attributes);
                },
                $this->fields
            ),
            'fields' => $this->formatFields($includeTranslations),
        ];
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    private function formatFields(bool $includeTranslations): array
    {
        $nodes = [];
        $childrenByParent = [];

        foreach ($this->fields as $field) {
            if (! $field instanceof DataFieldData) {
                continue;
            }

            $dependsOn = $field->attributes['depends_on_field'] ?? null;
            $dependsOnValues = $field->attributes['depends_on_values'] ?? [];

            $nodes[$field->attributes['name']] = [
                'payload' => $this->makeFieldPayload($field, $includeTranslations),
                'depends_on_field' => $dependsOn,
                'depends_on_values' => $dependsOnValues,
            ];
        }

        foreach ($nodes as $name => $node) {
            $parent = $node['depends_on_field'];

            if ($parent === null || ! isset($nodes[$parent])) {
                continue;
            }

            foreach ($node['depends_on_values'] as $value) {
                $childrenByParent[$parent][$value][] = $name;
            }
        }

        $fields = [];
        foreach ($nodes as $name => $node) {
            if ($node['depends_on_field'] !== null) {
                continue;
            }

            $fields[] = $this->resolveFieldNode($name, $nodes, $childrenByParent);
        }

        return $fields;
    }

    /**
     * @param  array<string, array<string, mixed>>  $nodes
     * @param  array<string, array<string, array<int, string>>>  $childrenByParent
     * @return array<string, mixed>
     */
    private function resolveFieldNode(string $name, array $nodes, array $childrenByParent): array
    {
        if (! isset($nodes[$name])) {
            return [];
        }

        $payload = $nodes[$name]['payload'];
        $dependencies = [];

        if (isset($childrenByParent[$name])) {
            foreach ($childrenByParent[$name] as $value => $childNames) {
                $childFields = [];
                foreach ($childNames as $childName) {
                    $childFields[] = $this->resolveFieldNode($childName, $nodes, $childrenByParent);
                }

                $dependencies[] = [
                    'value' => $value,
                    'fields' => array_values(array_filter($childFields)),
                ];
            }

            $payload['has_dependencies'] = true;
            $payload['dependencies'] = $this->orderDependencies(
                $dependencies,
                $payload['options'] ?? null
            );
        } else {
            $payload['has_dependencies'] = false;
        }

        if (! $payload['has_dependencies']) {
            unset($payload['dependencies']);
        }

        return $payload;
    }

    /**
     * @param  array<int, array<string, mixed>>  $dependencies
     * @param  array<int, string>|null  $options
     * @return array<int, array{value: string, fields: array<int, array<string, mixed>>}>
     */
    private function orderDependencies(array $dependencies, ?array $options): array
    {
        $indexed = [];

        foreach ($dependencies as $dependency) {
            $value = $dependency['value'];

            if (! isset($indexed[$value])) {
                $indexed[$value] = $dependency;
                continue;
            }

            $indexed[$value]['fields'] = array_merge($indexed[$value]['fields'], $dependency['fields']);
        }

        $ordered = [];

        if (is_array($options)) {
            foreach ($options as $optionValue) {
                $ordered[] = $indexed[$optionValue] ?? [
                    'value' => $optionValue,
                    'fields' => [],
                ];

                unset($indexed[$optionValue]);
            }
        }

        foreach ($indexed as $remaining) {
            $ordered[] = $remaining;
        }

        return $ordered;
    }

    private function makeFieldPayload(DataFieldData $field, bool $includeTranslations): array
    {
        $fieldData = $field->attributes;

        unset($fieldData['is_depended'], $fieldData['depends_on_field'], $fieldData['depends_on_values']);

        if ($includeTranslations) {
            $fieldData['translations'] = $field->translations;
        }

        return $fieldData;
    }
}
