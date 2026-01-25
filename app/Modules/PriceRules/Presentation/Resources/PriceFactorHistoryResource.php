<?php

namespace App\Modules\PriceRules\Presentation\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PriceFactorHistoryResource extends JsonResource
{
    /**
     * @param  \Illuminate\Http\Request  $request
     * @return array<string, mixed>
     */
    public function toArray($request): array
    {
        return [
            'id' => $this->attributes['id'] ?? null,
            'supplier_id' => $this->attributes['supplier_id'] ?? null,
            'user_id' => $this->attributes['user_id'] ?? null,
            'factor' => $this->attributes['factor'] ?? null,
            'status' => $this->attributes['status'] ?? null,
            'status_label' => $this->attributes['status_label'] ?? null,
            'parent_factor_id' => $this->attributes['parent_factor_id'] ?? null,
            'notes' => $this->attributes['notes'] ?? null,
            'user' => $this->attributes['user'] ?? null,
            'parent_factor' => $this->attributes['parent_factor'] ?? null,
            'products' => $this->products ?? [],
            'created_at' => $this->attributes['created_at'] ?? null,
            'updated_at' => $this->attributes['updated_at'] ?? null,
        ];
    }
}

