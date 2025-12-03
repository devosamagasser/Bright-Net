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

        $hide = $request->is('*/families/*/products') && $request->isMethod('GET');

        dd($this['roots']);
        return [
            'id' => $this->attributes['id'] ?? null,
            // 'roots' => $this->when(!$hide, $this->roots),
            'data_template_id' => $this->attributes['data_template_id'] ?? null,
            'code' => $this->attributes['code'] ?? null,
            'stock' => $this->attributes['stock'] ?? null,
            'disclaimer' => $this->attributes['disclaimer'] ?? null,
            'name' => $this->attributes['name'] ?? null,
            'description' => $this->attributes['description'] ?? null,
            'color' => $this->when($this->attributes['color'], fn() => $this->attributes['color']),
            'style' => $this->when($this->attributes['style'], fn() => $this->attributes['style']),
            'manufacturer' => $this->when($this->attributes['manufacturer'], fn() => $this->attributes['manufacturer']),
            'application' => $this->when($this->attributes['application'], fn() => $this->attributes['application']),
            'origin' => $this->when($this->attributes['origin'], fn() => $this->attributes['origin']),
            'translations' => $this->when(
                $request->is('*/products/*') && $request->method() === 'GET',
             fn() => $this->translations ?? []),
            'values' => array_values(array_filter(array_map(
                static function (ProductValueData $value) use ($hide) {

                    if ($hide && !$value->field['is_filterable']) {
                        return null;
                    }

                    return [
                        'field' => $value->field,
                        'value' => $value->value,
                    ];
                },
                $this->values
            ))),
            'prices' => array_map(
                static fn (ProductPriceData $price) => $price->attributes,
                $this->prices
            ),
            'accessories' => $this->accessories ?? [],
            'media' => $this->media ?? [],
        ];
    }
}
