<?php

namespace App\Modules\AccessControl\Domain\Repositories;

use Illuminate\Contracts\Auth\Authenticatable;

interface PlatformUserRepositoryInterface
{
    public function findByEmail(string $email): ?Authenticatable;
}
