<?php
namespace App\Modules\Products\Domain\ValueObjects;

enum DeliveryTimeUnit: string
{
    case DAYS = 'days';
    case WEEKS = 'weeks';
    case MONTHS = 'months';

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
        return array_map(static fn (self $unit) => $unit->value, self::cases());
    }
}
