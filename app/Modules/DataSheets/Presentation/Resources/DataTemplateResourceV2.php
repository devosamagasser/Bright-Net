<?php

namespace App\Modules\DataSheets\Presentation\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Modules\DataSheets\Application\DTOs\DataFieldData;

class DataTemplateResourceV2 extends JsonResource
{
    /**
     * @param  \Illuminate\Http\Request  $request
     * @return array<string, mixed>
     */
    public function toArray($request): array
    {
        $includeTranslations = $request->is('api/data-templates/*');

        // 1. تحويل الـ fields لمجموعة (Collection) وعمل map لها أولاً
        $processedFields = collect($this->fields)->map(function (DataFieldData $field) use ($includeTranslations) {
            if ($includeTranslations) {
                return array_merge($field->attributes, ['translations' => $field->translations]);
            }
            return $field->attributes;
        });

        // 2. عمل grouping بناءً على الـ key اللي اسمه 'group'
        $groupedFields = $processedFields->groupBy('group');

        return [
            'id' => $this->attributes['id'] ?? null,
            'subcategory_id' => $this->attributes['subcategory_id'] ?? null,
            'name' => $this->attributes['name'],
            'description' => $this->attributes['description'],
            'type' => $this->attributes['type'] ?? null,
            'translations' => $this->when(
                $includeTranslations || $request->isMethod('GET'),
                $this->translations
            ),
            // عرض الحقول مجمعة
            'fields' => $groupedFields,
        ];
    }
}
