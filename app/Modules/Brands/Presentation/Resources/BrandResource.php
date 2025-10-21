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
        return [
            'id' => $this->id,
            'name' => $this->name,
            'cover' => $this->cover,
            'region' => $this->region,
            'solution_ids' => $this->when($this->solutionIds, $this->solutionIds),
            'department_ids' => $this->when($this->departmentIds, $this->departmentIds),
            'solutions' => $this->when($this->solutions, $this->solutions),
            'created_at' => $this->createdAt,
            'updated_at' => $this->updatedAt,
        ];
    }
}
