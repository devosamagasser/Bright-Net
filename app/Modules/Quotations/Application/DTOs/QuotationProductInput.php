<?php

namespace App\Modules\Quotations\Application\DTOs;

class QuotationProductInput
{
    /**
     * @param  array<int, QuotationAccessoryInput>  $accessories
     */
    private function __construct(
        public readonly int $productId,
        public readonly ?int $quantity,
        public readonly ?string $itemRef,
        public readonly ?int $position,
        public readonly ?string $notes,
        public readonly ?float $price,
        public readonly ?float $discount,
        public readonly ?string $currency,
        public readonly ?string $deliveryTimeUnit,
        public readonly ?string $deliveryTimeValue,
        public readonly ?bool $vatIncluded,
        private readonly array $accessories,
    ) {
    }

    /**
     * @param  array<string, mixed>  $data
     */
    public static function fromArray(array $data): self
    {

        return new self(
            productId: (int) $data['product_id'],
            quantity: array_key_exists('quantity', $data) ? (int) $data['quantity'] : null,
            itemRef: $data['item_ref'] ?? null,
            position: array_key_exists('position', $data) ? (int) $data['position'] : null,
            notes: $data['notes'] ?? null,
            price: array_key_exists('price', $data) ? (float) $data['price'] : null,
            discount: array_key_exists('discount', $data) ? (float) $data['discount'] : null,
            currency: $data['currency'] ?? null,
            deliveryTimeUnit: $data['delivery_time_unit'] ?? null,
            deliveryTimeValue: $data['delivery_time_value'] ?? null,
            vatIncluded: array_key_exists('vat_included', $data) ? (bool) $data['vat_included'] : null,
            accessories: array_map(
                static fn (array $accessory): QuotationAccessoryInput => QuotationAccessoryInput::fromArray($accessory),
                $data['accessories'] ?? []
            ),
        );
    }

    /**
     * @return array<string, mixed>
     */
    public function attributes(): array
    {
        return array_filter([
            'quantity' => $this->quantity,
            'item_ref' => $this->itemRef,
            'position' => $this->position,
            'notes' => $this->notes,
            'price' => $this->price,
            'discount' => $this->discount,
            'currency' => $this->currency,
            'delivery_time_unit' => $this->deliveryTimeUnit,
            'delivery_time_value' => $this->deliveryTimeValue,
            'vat_included' => $this->vatIncluded,
        ], static fn ($value) => $value !== null);
    }

    /**
     * @return array<int, QuotationAccessoryInput>
     */
    public function accessories(): array
    {
        return $this->accessories;
    }
}
