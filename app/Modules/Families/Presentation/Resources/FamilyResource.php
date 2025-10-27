<?php

namespace App\Modules\Families\Presentation\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Modules\Families\Application\DTOs\FamilyData;

/**
 * @mixin FamilyData
 */
class FamilyResource extends JsonResource
{
    /**
     * @param  \Illuminate\Http\Request  $request
     * @return array<string, mixed>
     */
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'subcategory_id' => $this->subcategoryId,
            'supplier_id' => $this->supplierId,
            'data_template_id' => $this->dataTemplateId,
            'name' => $this->name,
            'description' => $this->description,
            'translations' => $this->translations,
            'values' => $this->values,
            'images' => $this->images,
            'created_at' => $this->createdAt,
            'updated_at' => $this->updatedAt,
        ];
    }
}
