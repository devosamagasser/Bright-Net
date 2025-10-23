<?php

namespace App\Modules\AccessControl\Infrastructure\Persistence\Eloquent;

use App\Models\User;
use App\Modules\AccessControl\Domain\Repositories\PlatformUserRepositoryInterface;
use Illuminate\Contracts\Auth\Authenticatable;

final class EloquentPlatformUserRepository implements PlatformUserRepositoryInterface
{
    public function findByEmail(string $email): ?Authenticatable
    {
        return User::query()->where('email', $email)->first();
    }
}
