<?php

namespace App\Modules\DataSheets\Domain\ValueObjects;

enum DataFieldType: string
{
    case TEXT = 'text';
    case NUMBER = 'number';
    case SELECT = 'select';
    case BOOLEAN = 'boolean';
    case DATE = 'date';
    case MULTISELECT = 'multiselect';
    case GROUPEDSELECT = 'groupedselect';

    public static function values(): array
    {
        return array_map(static fn (self $type) => $type->value, self::cases());
    }

    public static function selectable(): array
    {
        return [self::SELECT, self::MULTISELECT];
    }

    public function requiresOptions(): bool
    {
        return in_array($this, self::selectable(), true);
    }

    public function validation(): string
    {
        return match ($this) {
            self::TEXT => 'string',
            self::NUMBER => 'numeric',
            self::SELECT => 'string',
            self::MULTISELECT => 'array',
            self::BOOLEAN => 'boolean',
            self::DATE => 'date',
        };
    }
}
