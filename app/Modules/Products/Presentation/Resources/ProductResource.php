<?php

namespace App\Modules\Products\Presentation\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Modules\Products\Application\DTOs\{ProductValueData, ProductPriceData};
use RecursiveDirectoryIterator;

class ProductResource extends JsonResource
{
    /**
     * @param  \Illuminate\Http\Request  $request
     * @return array<string, mixed>
     */
    public function toArray($request): array
    {
        $collectionRequest = (bool) ($request->is('*/families/*/products') && $request->isMethod('GET'));
        $singleRequest = (bool) ($request->is('*/products/*') && $request->method() === 'GET');
        $attributes = $this->attributes;    
        return [
            'id' => $attributes['id'] ?? null,
            'roots' => $this->when(!$collectionRequest, $this->roots),
            'data_template_id' => $attributes['data_template_id'] ?? null,
            'code' => $attributes['code'] ?? null,
            'stock' => $attributes['stock'] ?? null,
            'disclaimer' => $attributes['disclaimer'] ?? null,
            'name' => $attributes['name'] ?? null,
            'description' => $attributes['description'] ?? null,
            'color' => $this->when($attributes['color'], fn() => $attributes['color']),
            'style' => $this->when($attributes['style'], fn() => $attributes['style']),
            'manufacturer' => $this->when($attributes['manufacturer'], fn() => $attributes['manufacturer']),
            'application' => $this->when($attributes['application'], fn() => $attributes['application']),
            'origin' => $this->when($attributes['origin'], fn() => $attributes['origin']),
            'translations' => $this->when(
                condition: $singleRequest,
                value: fn() => $this->translations ?? []
            ),
            'values' => array_values(array_filter(
                    array: array_map(
                        callback: static function (ProductValueData $value) use ($collectionRequest) {
                            if ($collectionRequest && !$value->field['is_filterable']) {
                                return;
                            }
                            return [
                                'field' => $value->field,
                                'value' => $value->value,
                            ];
                        },
                        array: $this->values
                    )
                )
            ),
            'prices' => $this->when(
                condition: $singleRequest,
                value: fn() => array_map(
                    callback: static fn (ProductPriceData $price) => $price->attributes,
                    array: $this->prices
                )
            ),
            'accessories' => $this->when(
                condition: $singleRequest,
                value: fn() => $this->accessories ?? []
            ),
            'media' => $this->media ?? [],
        ];
    }
}
