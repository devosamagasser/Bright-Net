<?php

namespace App\Modules\Products\Presentation\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Modules\Products\Application\DTOs\ProductValueData;

class ProductDataSheetResource extends JsonResource
{
    /**
     * @param  \Illuminate\Http\Request  $request
     * @return array<string, mixed>
     */
    public function toArray($request): array
    {
        return [
            'id' => $this->attributes['id'] ?? null,
            'data_template_id' => $this->attributes['data_template_id'] ?? null,
            'code' => $this->attributes['code'] ?? null,
            'name' => $this->attributes['name'] ?? null,
            'description' => $this->attributes['description'] ?? null,
            'values' => array_map(
                static fn (ProductValueData $value) => [
                    'field' => $value->field,
                    'value' => $value->value,
                ],
                $this->values
            ),
        ];
    }
}
