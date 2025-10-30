<?php

namespace App\Modules\SupplierEngagements\Presentation\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Modules\Families\Application\DTOs\FamilyValueData;
use App\Modules\Families\Presentation\Resources\FamilyResource;


class SupplierSubcategoryResource extends JsonResource
{
    /**
     * @param  \Illuminate\Http\Request  $request
     * @return array<string, mixed>
     */
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'families' => $this->families->map(function ($family): array {
                return [
                    'id' => $family->id,
                    'data_template_id' => $family->data_template_id,
                    'name' => $family->name,
                    'description' => $family->description,
                    'image' => $family->getFirstMediaUrl('images'),
                    'values' => $family->fieldValues->map(function ($value): array {
                        return [
                            'type' => $value->field->type->value,
                            'value' => $value->value,
                        ];
                    })
                ];
            })
        ];
    }
}
