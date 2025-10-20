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
            'departments' => $this->when($this->departments, function () {
                return collect($this->departments)->map(function ($department) {
                    return [
                        'id' => $department['id'],
                        'name' => $department['name'],
                    ];
                })->values();
            }),
            'translations' => $this->when((request()->is('*/solutions/*') && request()->method() === 'GET'), $this->translations),
        ];
    }
}

