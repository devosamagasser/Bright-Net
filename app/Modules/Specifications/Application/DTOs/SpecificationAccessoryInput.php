<?php

namespace App\Modules\Specifications\Application\DTOs;

class SpecificationAccessoryInput
{
    private function __construct(
        public readonly int $accessoryId,
        public readonly ?int $quantity,
        public readonly ?string $itemRef,
        public readonly ?int $position,
        public readonly ?string $notes,
        public readonly ?string $type,
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
            itemRef: $data['item_ref'] ?? null,
            position: array_key_exists('position', $data) ? (int) $data['position'] : null,
            notes: $data['notes'] ?? null,
            type: $data['accessory_type'] ?? null,
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
            'accessory_type' => $this->type,
        ], static fn ($value) => $value !== null);
    }
}


