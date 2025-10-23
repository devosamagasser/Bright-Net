<?php

namespace App\Modules\Authentication\Application\DTOs;

class AuthData
{

    /**
     * @param  array<string, mixed>  $userData
     * @param string $token
     */
    private function __construct(
        public readonly array $userData,
        public readonly ?string $token = null,
    ) {
    }

    public static function fromModel(array $userData, ?string $token = null): self
    {
        return new self(
            userData: $userData,
            token: $token
        );
    }
}
