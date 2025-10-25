<?php

namespace App\Modules\SupplierEngagements\Presentation\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property array{
 *     supplier_brand_id:int,
 *     id:int,
 *     name:string|null,
 *     logo:string|null
 * } $resource
 */
class SupplierBrandResource extends JsonResource
{
    /**
     * @param  \Illuminate\Http\Request  $request
     * @return array<string, mixed>
     */
    public function toArray($request): array
    {
        return [
            'supplier_brand_id' => (int) ($this->resource['supplier_brand_id'] ?? 0),
            'id' => (int) ($this->resource['id'] ?? 0),
            'name' => $this->resource['name'] ?? null,
            'logo' => $this->resource['logo'] ?? null,
        ];
    }
}
