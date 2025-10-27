<?php

namespace App\Modules\Families\Application\DTOs;

use App\Modules\Families\Domain\Models\FamilyFieldValue;
use App\Modules\DataSheets\Domain\ValueObjects\DataFieldType;

class FamilyValueData
{
    /**
     * @param  array<string, mixed>  $field
     */
    private function __construct(
        public readonly array $field,
        public readonly mixed $value,
    ) {
    }

    public static function fromModel(FamilyFieldValue $value): self
    {
        $field = $value->field;
        $type = $field->type instanceof DataFieldType ? $field->type->value : $field->type;

        return new self(
            field: [
                'id' => $field->getKey(),
                'slug' => $field->slug,
                'type' => $type,
                'label' => $field->label,
                'placeholder' => $field->placeholder,
                'is_required' => (bool) $field->is_required,
                'is_filterable' => (bool) $field->is_filterable,
                'options' => $field->options ?? [],
                'position' => (int) ($field->position ?? 0),
            ],
            value: $value->value,
        );
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return [
            'field' => $this->field,
            'value' => $this->value,
        ];
    }
}
