<?php

namespace App\Modules\SupplierEngagements\Presentation\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property array{
 *     id:int,
 *     name:string|null
 * } $resource
 */
class SupplierSubcategoryResource extends JsonResource
{
    /**
     * @param  \Illuminate\Http\Request  $request
     * @return array<string, mixed>
     */
    public function toArray($request): array
    {
        return [
            'id' => (int) ($this->resource['id'] ?? 0),
            'name' => $this->resource['name'] ?? null,
        ];
    }
}
