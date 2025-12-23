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
        $product = $this['product'];
        return [
            'id' => $product->attributes['id'] ?? null,
            'roots' => $this->when(!$hide, $this['roots']),
            'data_template_id' => $product->attributes['data_template_id'] ?? null,
            'code' => $product->attributes['code'] ?? null,
            'stock' => $product->attributes['stock'] ?? null,
            'disclaimer' => $product->attributes['disclaimer'] ?? null,
            'name' => $product->attributes['name'] ?? null,
            'description' => $product->attributes['description'] ?? null,
            'color' => $this->when($product->attributes['color'], fn() => $product->attributes['color']),
            'style' => $this->when($product->attributes['style'], fn() => $product->attributes['style']),
            'manufacturer' => $this->when($product->attributes['manufacturer'], fn() => $product->attributes['manufacturer']),
            'application' => $this->when($product->attributes['application'], fn() => $product->attributes['application']),
            'origin' => $this->when($product->attributes['origin'], fn() => $product->attributes['origin']),
            'translations' => $this->when(
                $request->is('*/products/*') && $request->method() === 'GET',
             fn() => $product->translations ?? []),
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
                $product->values
            ))),
            'prices' => $this->when(
                $request->is('*/products/*') && $request->method() === 'GET',
                fn() => array_map(
                    static fn (ProductPriceData $price) => $price->attributes,
                    $product->prices
                    )
            ),
            'accessories' => $this->when(
                $request->is('*/products/*') && $request->method() === 'GET',
                fn() => $product->accessories ?? []
            ),
            'media' => $product->media ?? [],
        ];
    }
}
