<?php
namespace App\Modules\Authentication\Domain\ValueObjects;

use App\Models\User;
use App\Models\CompanyUser;
use Illuminate\Database\Eloquent\Model;

enum UserType: string
{
    case PLATFORM = 'platform';
    case COMPANY = 'company';

    public static function fromModel(Model $model)
    {
        return match ($model::class) {
            User::class => self::PLATFORM,
            CompanyUser::class => self::COMPANY,
            default => throw new \InvalidArgumentException('Unknown user model type'),
        };
    }

}



