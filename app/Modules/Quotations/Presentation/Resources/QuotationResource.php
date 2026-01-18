<?php

namespace App\Modules\Quotations\Presentation\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Modules\Products\Domain\ValueObjects\PriceCurrency;

class QuotationResource extends JsonResource
{
    /**
     * @param  \Illuminate\Http\Request  $request
     * @return array<string, mixed>
     */
    public function toArray($request): array
    {
        $currency = $this->currency instanceof PriceCurrency
            ? $this->currency->value
            : $this->currency;

        return [
            'id' => (int) $this->getKey(),
            'reference' => $this->reference,
            // 'title' => $this->title,
            // 'status' => $this->status?->value,
            // 'status_label' => $this->status?->label(),
            'currency' => $currency,
            'subtotal' => (float) $this->subtotal,
            'discount_total' => (float) $this->discount_total,
            'total' => (float) $this->total,
            'discount_applied' => (bool) $this->discount_applied,
            'vat_applied' => (bool) $this->vat_applied,
            'general_notes' => $this->general_notes ?? [],
            'warranty' => $this->warranty ?? [],
            'warranty_and_payments' => $this->warranty_and_payments ?? [],
            // 'notes' => $this->notes,
            // 'valid_until' => $this->valid_until?->toDateString(),
            // 'meta' => $this->meta ?? [],
            // 'supplier' => $this->whenLoaded('supplier', fn () => [
            //     'id' => $this->supplier?->getKey(),
            //     'name' => $this->supplier?->company?->name,
            // ]),
            // 'company' => $this->whenLoaded('company', fn () => [
            //     'id' => $this->company?->getKey(),
            //     'name' => $this->company?->name,
            // ]),
            'products' => QuotationProductResource::collection(
                $this->whenLoaded('products')
            ),
        ];
    }
}
