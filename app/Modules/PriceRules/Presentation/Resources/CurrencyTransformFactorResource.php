<?php

namespace App\Modules\PriceRules\Presentation\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CurrencyTransformFactorResource extends JsonResource
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
            'from' => $this->attributes['from'] ?? null,
            'to' => $this->attributes['to'] ?? null,
            'factor' => $this->attributes['factor'] ?? null,
            'created_at' => $this->attributes['created_at'] ?? null,
            'updated_at' => $this->attributes['updated_at'] ?? null,
        ];
    }
}

