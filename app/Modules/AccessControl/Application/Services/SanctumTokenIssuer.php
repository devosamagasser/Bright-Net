<?php

namespace App\Modules\AccessControl\Application\Services;

use App\Modules\AccessControl\Application\Contracts\TokenIssuerInterface;
use App\Modules\AccessControl\Application\DTOs\TokenResult;

class SanctumTokenIssuer implements TokenIssuerInterface
{
    public function issueToken(object $user, string $tokenName): TokenResult
    {
        $abilities = $this->resolveAbilities($user);
        $token = $user->createToken($tokenName, $abilities);

        return new TokenResult($token->plainTextToken, $abilities);
    }

    /**
     * @param  object  $user  Expected to use the HasRoles trait.
     * @return array<int, string>
     */
    private function resolveAbilities(object $user): array
    {
        if (! method_exists($user, 'getAllPermissions')) {
            return ['*'];
        }

        $permissions = $user->getAllPermissions()->pluck('name')->values()->all();

        if ($permissions === []) {
            return ['*'];
        }

        return $permissions;
    }
}
