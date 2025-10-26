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
            'type' => $this->attributes['type'] ?? null,
            'translations' => $this->translations,
            'fields' => array_map(function (DataFieldData $field) {
                return array_merge(
                    $field->attributes,
                    [
                        'translations' => $field->translations,
                    ],
                );
            }, $this->fields),
        ];
    }
}
