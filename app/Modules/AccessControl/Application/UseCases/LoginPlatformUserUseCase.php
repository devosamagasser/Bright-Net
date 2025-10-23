<?php

namespace App\Modules\AccessControl\Application\UseCases;

use App\Modules\AccessControl\Application\Contracts\TokenIssuerInterface;
use App\Modules\AccessControl\Application\DTOs\{LoginData, LoginResult};
use App\Modules\AccessControl\Application\Exceptions\InvalidCredentialsException;
use App\Modules\AccessControl\Domain\Repositories\PlatformUserRepositoryInterface;
use Illuminate\Contracts\Hashing\Hasher;

final class LoginPlatformUserUseCase
{
    public function __construct(
        private readonly PlatformUserRepositoryInterface $users,
        private readonly TokenIssuerInterface $tokens,
        private readonly Hasher $hasher,
    ) {
    }

    public function handle(LoginData $data): LoginResult
    {
        $user = $this->users->findByEmail($data->email);

        if (! $user || ! $this->hasher->check($data->password, $user->getAuthPassword())) {
            throw new InvalidCredentialsException();
        }

        $token = $this->tokens->issueToken($user, $data->deviceName);

        $isOwner = method_exists($user, 'getAttribute')
            ? (bool) $user->getAttribute('is_owner')
            : (bool) ($user->is_owner ?? false);

        return new LoginResult($user, $token, [
            'is_owner' => $isOwner,
        ]);
    }
}
