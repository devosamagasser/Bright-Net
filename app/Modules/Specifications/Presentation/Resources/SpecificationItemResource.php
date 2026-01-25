<?php

namespace App\Modules\Specifications\Presentation\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class SpecificationItemResource extends JsonResource
{
    /**
     * @param  \Illuminate\Http\Request  $request
     * @return array<string, mixed>
     */
    public function toArray($request): array
    {
        return [
            'id' => (int) $this->getKey(),
            'item_ref' => $this->item_ref,
            'position' => (int) $this->position,
            'product_id' => $this->product_id,
            'product_code' => $this->when($request->show_reference, $this->product_code),
            'product_name' => $this->product_name,
            'product_description' => $this->product_description,
            'product_origin' => $this->when($request->show_reference, $this->product_origin),
            'brand_id' => $this->when($request->show_reference, $this->brand_id),
            'brand_name' => $this->when($request->show_reference, $this->brand_name),
            'notes' => $this->notes,
            'quantity' => $this->when($request->show_quantity, (int) ($this->quantity ?? 0)),
            'approval' => $this->when($request->show_approval, 'or approved equal'),
                'accessories' => SpecificationItemAccessoryResource::collection(
                $this->whenLoaded('accessories')
            ),
        ];
    }
}


