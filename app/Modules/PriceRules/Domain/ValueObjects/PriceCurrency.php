<?php
namespace App\Modules\PriceRules\Domain\ValueObjects;

enum PriceCurrency: string
{
    case EGP = 'EGP';
    case USD = 'USD';
    case EUR = 'EUR';
    case SR = 'SR';

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
        return array_map(static fn (self $currency) => $currency->value, self::cases());
    }
}
