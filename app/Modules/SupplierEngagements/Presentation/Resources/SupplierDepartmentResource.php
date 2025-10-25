<?php

namespace App\Modules\SupplierEngagements\Presentation\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property array{
 *     supplier_department_id:int,
 *     id:int,
 *     name:string|null
 * } $resource
 */
class SupplierDepartmentResource extends JsonResource
{
    /**
     * @param  \Illuminate\Http\Request  $request
     * @return array<string, mixed>
     */
    public function toArray($request): array
    {
        return [
            'supplier_department_id' => (int) ($this->resource['supplier_department_id'] ?? 0),
            'id' => (int) ($this->resource['id'] ?? 0),
            'name' => $this->resource['name'] ?? null,
        ];
    }
}
