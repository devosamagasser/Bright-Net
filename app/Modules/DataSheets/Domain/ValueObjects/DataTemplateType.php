<?php

namespace App\Modules\DataSheets\Domain\ValueObjects;

enum DataTemplateType: string
{
    case FAMILY = 'family';
    case PRODUCT = 'product';

    /**
     * @return array<int, string>
     */
    public static function values(): array
    {
        return array_map(static fn (self $type) => $type->value, self::cases());
    }

    public function isFamily(): bool
    {
        return $this === self::FAMILY;
    }

    public function isProduct(): bool
    {
        return $this === self::PRODUCT;
    }
}
