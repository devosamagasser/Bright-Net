<?php

namespace App\Modules\Brands\Presentation\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Modules\Brands\Domain\Models\Brand;
use App\Modules\Brands\Application\DTOs\BrandData;

class BrandResource extends JsonResource
{
    /**
     * @param  \Illuminate\Http\Request  $request
     * @return array<string, mixed>
     */
    public function toArray($request): array
    {
        $resource = $this->resource instanceof Brand
            ? BrandData::fromModel($this->resource)
            : $this->resource;

        if ($resource instanceof BrandData) {
            return [
                'id' => $resource->id,
                'name' => $resource->name,
                'region_id' => $resource->regionId,
                'region' => $resource->region,
                'solution_ids' => $resource->solutionIds,
                'department_ids' => $resource->departmentIds,
                'solutions' => $resource->solutions,
                'created_at' => $resource->createdAt,
                'updated_at' => $resource->updatedAt,
            ];
        }

        return [
            'id' => $this->id,
            'name' => $this->name,
            'region_id' => $this->region_id,
            'region' => $this->whenLoaded('region', function () {
                return [
                    'id' => $this->region?->getKey(),
                    'name' => $this->region?->name,
                ];
            }),
            'solution_ids' => $this->whenLoaded('solutions', fn () => $this->solutions->pluck('id')->map(fn ($id) => (int) $id)->all()),
            'department_ids' => $this->whenLoaded('departments', fn () => $this->departments->pluck('id')->map(fn ($id) => (int) $id)->all()),
            'solutions' => $this->whenLoaded('solutions', function () {
                $departmentsBySolution = $this->whenLoaded('departments')
                    ? $this->departments->groupBy('solution_id')
                    : collect();

                return $this->solutions->map(function ($solution) use ($departmentsBySolution) {
                    $solutionDepartments = $departmentsBySolution[$solution->getKey()] ?? collect();

                    return [
                        'id' => $solution->getKey(),
                        'name' => $solution->name,
                        'departments' => collect($solutionDepartments)->map(static function ($department) {
                            return [
                                'id' => $department->getKey(),
                                'name' => $department->name,
                            ];
                        })->values()->all(),
                    ];
                })->values()->all();
            }),
        ];
    }
}
