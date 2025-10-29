<?php

namespace App\Modules\SupplierEngagements\Presentation\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property array{
 *     supplier_department: array{id:int, name:string|null},
 *     id:int,
 *     name:string|null,
 *     families?: array<int, array{id:int, name:string|null}>
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
        $supplierDepartment = $this->resource['supplier_department'] ?? [];
        $families = $this->resource['families'] ?? [];

        if (! is_array($families)) {
            $families = [];
        }

        return [
            'supplier_department' => [
                'id' => (int) ($supplierDepartment['id'] ?? 0),
                'name' => $supplierDepartment['name'] ?? null,
            ],
            'id' => (int) ($this->resource['id'] ?? 0),
            'name' => $this->resource['name'] ?? null,
            'families' => array_map(static function (array $family): array {
                return [
                    'id' => (int) ($family['id'] ?? 0),
                    'name' => $family['name'] ?? null,
                ];
            }, $families),
        ];
    }
}
