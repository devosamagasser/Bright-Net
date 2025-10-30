<?php
namespace App\Modules\Products\Domain\ValueObjects;

enum AccessoryType: string
{
    case NEEDED = 'needed';
    case OPTIONAL = 'optional';
    case INCLUDED = 'included';

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

    /**
     * @return array<int, string>
     */
    public static function values(): array
    {
        return array_map(static fn (self $type) => $type->value, self::cases());
    }
}
