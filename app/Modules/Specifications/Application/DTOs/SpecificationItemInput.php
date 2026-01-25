<?php

namespace App\Modules\Specifications\Application\DTOs;

class SpecificationItemInput
{
    /**
     * @param  array<int, SpecificationAccessoryInput>  $accessories
     */
    private function __construct(
        public readonly int $productId,
        public readonly ?int $quantity,
        public readonly ?string $itemRef,
        public readonly ?int $position,
        public readonly ?string $notes,
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
            accessories: array_map(
                static fn (array $accessory): SpecificationAccessoryInput => SpecificationAccessoryInput::fromArray($accessory),
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
        ], static fn ($value) => $value !== null);
    }

    /**
     * @return array<int, SpecificationAccessoryInput>
     */
    public function accessories(): array
    {
        return $this->accessories;
    }
}


