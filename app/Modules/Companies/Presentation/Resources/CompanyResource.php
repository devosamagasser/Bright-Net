<?php

namespace App\Modules\Companies\Presentation\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Modules\Companies\Application\DTOs\CompanyData;

/** @mixin CompanyData */
class CompanyResource extends JsonResource
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
            'type' => $this->type,
            'type_label' => $this->typeLabel,
            'logo' => $this->logo,
            'created_at' => $this->createdAt,
            'updated_at' => $this->updatedAt,
        ];
    }
}
