<?php

namespace App\Modules\Favourites\Presentation\Resources;

use App\Modules\Favourites\Application\DTOs\CollectionData;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin CollectionData
 */
class CollectionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array<string, mixed>
     */
    public function toArray($request): array
    {
        /** @var CollectionData $data */
        $data = $this->resource;

        return [
            'id' => $data->id,
            'name' => $data->name,
            'company_id' => $data->companyId,
            'company_name' => $data->companyName,
            'products_count' => $data->productsCount,
            'products' => $data->products,
        ];
    }
}

