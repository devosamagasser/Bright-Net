<?php
namespace App\Modules\Authentication\Application\DTOs;


class AuthInput
{
    public function __construct(
        public readonly array $credentials,
        public readonly string $type,
    ) {
    }

    public static function fromArray(array $credentials, string $type): self
    {
        return new self(
            type: $type,
            credentials: $credentials,
        );
    }
}
