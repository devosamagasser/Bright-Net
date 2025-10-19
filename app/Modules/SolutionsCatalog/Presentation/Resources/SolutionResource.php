<?php

namespace App\Modules\SolutionsCatalog\Presentation\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class SolutionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array<string, mixed>
     */
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'departments' => collect($this->departments)->map(function ($department) {
                return [
                    'id' => $department['id'],
                    'name' => $department['name'],
                    'subcategories' => collect($department['subcategories'])->map(function ($subcategory) {
                        return [
                            'id' => $subcategory['id'],
                            'name' => $subcategory['name'],
                        ];
                    })->values(),
                ];
            })->values(),
            'created_at' => $this->createdAt,
            'updated_at' => $this->updatedAt,
        ];
    }
}

