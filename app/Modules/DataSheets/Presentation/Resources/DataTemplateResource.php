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
        return [
            'id' => $this->attributes['id'] ?? null,
            'subcategory_id' => $this->attributes['subcategory_id'] ?? null,
            'name' => $this->attributes['name'],
            'description' => $this->attributes['description'],
            'type' => $this->attributes['type'] ?? null,
            'translations' => $this->when(
            request()->is('*/data-templates/*') && request()->method() === 'GET',
             $this->translations
            ),
            'fields' => $this->when(
            request()->is('*/family-data-templates/*', '*/product-data-templates/*') && request()->method() === 'GET',
            array_map(function (DataFieldData $field) {
                return array_merge(
                        $field->attributes,
                        [
                            'translations' => $field->translations,
                        ],
                    );
                }, $this->fields),
            ),
        ];
    }
}
