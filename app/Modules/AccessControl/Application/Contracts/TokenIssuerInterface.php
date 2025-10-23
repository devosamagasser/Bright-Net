<?php

namespace App\Modules\AccessControl\Application\Contracts;

use App\Modules\AccessControl\Application\DTOs\TokenResult;

interface TokenIssuerInterface
{
    /**
     * @param  object  $user  Should implement both Sanctum's HasApiTokens and Spatie's HasRoles traits.
     */
    public function issueToken(object $user, string $tokenName): TokenResult;
}
