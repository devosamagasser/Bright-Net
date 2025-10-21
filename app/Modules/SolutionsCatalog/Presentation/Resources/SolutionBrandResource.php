<?php

namespace App\Modules\SolutionsCatalog\Presentation\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class SolutionBrandResource extends JsonResource
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
            'logo' => $this->logo,
            'region' => $this->region,
            'departments' => $this->departments,
        ];
    }
}
