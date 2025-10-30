<?php

namespace App\Modules\Families\Presentation\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Modules\Families\Application\DTOs\FamilyValueData;

class FamilyResource extends JsonResource
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
            'data_template_id' => $this->attributes['data_template_id'] ?? null,
            'name' => $this->attributes['name'] ?? null,
            'translations' => $this->translations,
            'values' => array_map(
                static fn (FamilyValueData $value) => [
                    'field' => $value->field,
                    'value' => $value->value,
                ],
                $this->values
            ),
        ];
    }
}
