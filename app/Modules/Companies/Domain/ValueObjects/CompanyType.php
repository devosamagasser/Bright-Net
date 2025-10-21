<?php
namespace App\Modules\Companies\Domain\ValueObjects;

enum CompanyType: string
{
    case SUPPLIER = 'supplier';
    case CONTRACTOR = 'contractor';
    case CONSULTANT = 'consultant';

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
