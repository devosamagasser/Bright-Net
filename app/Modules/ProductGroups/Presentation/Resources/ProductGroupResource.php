<?php

namespace App\Modules\Products\Presentation\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ProductGroupResource extends JsonResource
{
    /**
     * @param  \Illuminate\Http\Request  $request
     * @return array<string, mixed>
     */
    public function toArray($request): array
    {
        return [
            'id' => $this->attributes['id'] ?? null,
            'family_id' => $this->attributes['family_id'] ?? null,
            'data_template_id' => $this->attributes['data_template_id'] ?? null,
            'supplier_id' => $this->attributes['supplier_id'] ?? null,
            'solution_id' => $this->attributes['solution_id'] ?? null,
            'subcategory_id' => $this->attributes['subcategory_id'] ?? null,
            'created_at' => $this->attributes['created_at'] ?? null,
            'updated_at' => $this->attributes['updated_at'] ?? null,
            'first_product' => $this->when($this->firstProduct !== null, $this->firstProduct),
        ];
    }
}

