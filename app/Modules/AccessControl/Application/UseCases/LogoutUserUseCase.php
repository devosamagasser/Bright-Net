<?php

namespace App\Modules\AccessControl\Application\UseCases;

final class LogoutUserUseCase
{
    public function handle(?object $user): void
    {
        if (! $user || ! method_exists($user, 'currentAccessToken')) {
            return;
        }

        $token = $user->currentAccessToken();

        if ($token !== null) {
            $token->delete();
        }
    }
}
