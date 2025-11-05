<?php

namespace App\Modules\Quotations\Application\DTOs;

class QuotationAccessoryInput
{
    private function __construct(
        public readonly int $accessoryId,
        public readonly ?int $quantity,
        public readonly ?string $type,
        public readonly ?float $price,
        public readonly ?float $discount,
        public readonly ?string $currency,
        public readonly ?string $itemRef,
        public readonly ?int $position,
        public readonly ?string $notes,
        public readonly ?string $deliveryTimeUnit,
        public readonly ?string $deliveryTimeValue,
        public readonly ?bool $vatIncluded,
    ) {
    }

    /**
     * @param  array<string, mixed>  $data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            accessoryId: (int) $data['accessory_id'],
            quantity: array_key_exists('quantity', $data) ? (int) $data['quantity'] : null,
            type: $data['accessory_type'] ?? null,
            price: array_key_exists('price', $data) ? (float) $data['price'] : null,
            discount: array_key_exists('discount', $data) ? (float) $data['discount'] : null,
            currency: $data['currency'] ?? null,
            itemRef: $data['item_ref'] ?? null,
            position: array_key_exists('position', $data) ? (int) $data['position'] : null,
            notes: $data['notes'] ?? null,
            deliveryTimeUnit: $data['delivery_time_unit'] ?? null,
            deliveryTimeValue: $data['delivery_time_value'] ?? null,
            vatIncluded: array_key_exists('vat_included', $data) ? (bool) $data['vat_included'] : null,
        );
    }

    /**
     * @return array<string, mixed>
     */
    public function attributes(): array
    {
        return array_filter([
            'quantity' => $this->quantity,
            'accessory_type' => $this->type,
            'price' => $this->price,
            'discount' => $this->discount,
            'currency' => $this->currency,
            'item_ref' => $this->itemRef,
            'position' => $this->position,
            'notes' => $this->notes,
            'delivery_time_unit' => $this->deliveryTimeUnit,
            'delivery_time_value' => $this->deliveryTimeValue,
            'vat_included' => $this->vatIncluded,
        ], static fn ($value) => $value !== null);
    }
}
