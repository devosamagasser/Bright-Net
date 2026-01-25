<?php

namespace App\Modules\PriceRules\Domain\ValueObjects;

enum PriceFactorStatus: string
{
    case ACTIVE = 'active';
    case REVERTED = 'reverted';
    case INACTIVE = 'inactive';

    public function label(): string
    {
        return match($this) {
            self::ACTIVE => 'Active',
            self::REVERTED => 'Reverted',
            self::INACTIVE => 'Inactive',
        };
    }

    public static function values(): array
    {
        return array_map(static fn (self $status) => $status->value, self::cases());
    }
}

