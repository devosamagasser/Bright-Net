<?php

namespace App\Modules\Quotations\Domain\ValueObjects;

enum QuotationStatus
{
    const DRAFT = 'draft';
    const ACCEPTED = 'saved';
    const REJECTED = 'active';

    public function label(): string
    {
        return __("enums/static_keys.company_types.{$this->value}");
    }

    public static function options(): array
    {
        return array_map(fn ($type) => [
            'value' => $type->value,
            'label' => $type->label(),
        ], self::cases());
    }

}
