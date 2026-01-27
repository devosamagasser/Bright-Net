<?php

namespace App\Modules\SupplierEngagements\Presentation\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property array{
 *     supplier_solution_id:int,
 *     solution_id:int,
 *     solution: array{id:int,name:string}
 * } $resource
 */
class SupplierSolutionResource extends JsonResource
{
    /**
     * @param  \Illuminate\Http\Request  $request
     * @return array<string, mixed>
     */
    public function toArray($request): array
    {
        return [
            'supplier_solution_id' => (int) ($this->resource['supplier_solution_id'] ?? 0),
            'name' => $this->resource['solution']['name'] ?? null,
            'solution_id' => (int) ($this->resource['solution']['id'] ?? 0),
        ];
    }
}
