<?php

namespace App\Modules\Specifications\Presentation\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class SpecificationResource extends JsonResource
{
    /**
     * @param  \Illuminate\Http\Request  $request
     * @return array<string, mixed>
     */
    public function toArray($request): array
    {
        $request->merge([
            'show_quantity' => (bool) $this->show_quantity,
            'show_approval' => (bool) $this->show_approval,
            'show_reference' => (bool) $this->show_reference,
        ]);
        return [
            'id' => (int) $this->getKey(),
            'reference' => $this->reference,
            'title' => $this->title,
            'status' => $this->status?->value,
            'status_label' => $this->status?->label(),
            'general_notes' => $this->general_notes ?? [],
            'show_quantity' => (bool) $this->show_quantity,
            'show_approval' => (bool) $this->show_approval,
            'show_reference' => (bool) $this->show_reference,
            'items' => SpecificationItemResource::collection(
                $this->whenLoaded('items')
            ),
        ];
    }
}


