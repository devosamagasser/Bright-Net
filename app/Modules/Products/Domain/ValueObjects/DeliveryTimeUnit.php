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
}
