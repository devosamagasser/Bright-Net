<?php

namespace App\Modules\AccessControl\Application\DTOs;

/**
 * @phpstan-type ExtraUserData array<string, mixed>
 */
final class LoginResult
{
    /**
     * @param  object  $user
     * @param  ExtraUserData  $extraUserData
     */
    public function __construct(
        public readonly object $user,
        public readonly TokenResult $token,
        public readonly array $extraUserData = [],
    ) {
    }
}
