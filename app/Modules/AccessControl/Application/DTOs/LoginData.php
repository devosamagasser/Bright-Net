<?php

namespace App\Modules\AccessControl\Application\DTOs;

final class LoginData
{
    public function __construct(
        public readonly string $email,
        public readonly string $password,
        public readonly string $deviceName,
    ) {
    }
}
