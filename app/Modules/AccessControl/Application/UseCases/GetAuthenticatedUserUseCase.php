<?php

namespace App\Modules\AccessControl\Application\UseCases;

use Illuminate\Contracts\Auth\Factory as AuthFactory;

final class GetAuthenticatedUserUseCase
{
    public function __construct(private readonly AuthFactory $authFactory)
    {
    }

    /**
     * @param  array<int, string>  $relations
     */
    public function handle(string $guard, array $relations = []): ?object
    {
        $guardInstance = $this->authFactory->guard($guard);
        $user = $guardInstance->user();

        if ($user && $relations !== [] && method_exists($user, 'loadMissing')) {
            $user->loadMissing($relations);
        }

        return $user;
    }
}
