<?php

namespace App\Modules\Companies\Presentation\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Modules\Companies\Application\DTOs\CompanyData;

/**
 * @mixin CompanyData
 */
class CompanyResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array<string, mixed>
     */
    public function toArray($request): array
    {
        /** @var CompanyData $data */
        $data = $this->resource;

        return [
            'id' => $data->id,
            'name' => $data->name,
            'description' => $data->description,
            'type' => $data->type,
            'logo' => $data->logo,
            'profile' => $data->profile,
            'created_at' => $data->createdAt,
            'updated_at' => $data->updatedAt,
        ];
    }
}
