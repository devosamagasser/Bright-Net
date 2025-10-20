<?php

namespace App\Modules\Departments\Presentation\Resources;

use Illuminate\Support\{Carbon, Collection};
use Illuminate\Http\Resources\Json\JsonResource;
use App\Modules\Subcategories\Presentation\Resources\SubcategoryResource;

class DepartmentResource extends JsonResource
{
    /**
     * @param  \Illuminate\Http\Request  $request
     * @return array<string, mixed>
     */
    public function toArray($request): array
    {
        //($this);
        return [
            'id' => $this->id,
            'name' => $this->name,
            'solution_id' => $this->solution_id ?? $this->solutionId,
            'subcategories' => $this->when($this->subcategories, function () {
                return collect($this->subcategories)->map(function ($subcategory) {
                    return [
                        'id' => $subcategory['id'],
                        'name' => $subcategory['name'],
                    ];
                })->values();
            }),
            'translations' => $this->when(
            request()->is('*/departments/*') && request()->method() === 'GET',
             $this->translations
            ),
        ];
    }


}
