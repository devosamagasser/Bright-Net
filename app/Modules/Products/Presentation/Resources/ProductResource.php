<?php

namespace App\Modules\Products\Presentation\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Modules\Products\Application\DTOs\{ProductValueData, ProductPriceData};

class ProductResource extends JsonResource
{
    /**
     * @param  \Illuminate\Http\Request  $request
     * @return array<string, mixed>
     */
    public function toArray($request): array
    {
        return [
            'id' => $this->attributes['id'] ?? null,
            'family_id' => $this->attributes['family_id'] ?? null,
            'data_template_id' => $this->attributes['data_template_id'] ?? null,
            'code' => $this->attributes['code'] ?? null,
            'stock' => $this->attributes['stock'] ?? null,
            'disclaimer' => $this->attributes['disclaimer'] ?? null,
            'name' => $this->attributes['name'] ?? null,
            'description' => $this->attributes['description'] ?? null,
            'translations' => $this->when(
                $request->is('*/products/*') && $request->method() === 'GET',
             fn() => $this->translations ?? []),
            'values' => array_map(
                static fn (ProductValueData $value) => [
                    'field' => $value->field,
                    'value' => $value->value,
                ],
                $this->values
            ),
            'prices' => array_map(
                static fn (ProductPriceData $price) => $price->attributes,
                $this->prices
            ),
            'accessories' => $this->accessories ?? [],
            'colors' => $this->colors ?? [],
            'media' => $this->media ?? [],
        ];
    }
}
