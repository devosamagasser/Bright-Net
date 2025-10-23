<?php

namespace App\Modules\AccessControl\Presentation\Http\Transformers;

class AuthenticatedUserPayloadFactory
{
    /**
     * @param  object  $user  Expected to implement getRoleNames() and getAllPermissions().
     * @param  array<string, mixed>  $extra
     * @return array<string, mixed>
     */
    public function make(object $user, array $extra = []): array
    {
        $roles = method_exists($user, 'getRoleNames')
            ? $user->getRoleNames()->values()->all()
            : [];

        $permissions = method_exists($user, 'getAllPermissions')
            ? $user->getAllPermissions()->pluck('name')->values()->all()
            : [];

        $payload = [
            'id' => $user->getKey(),
            'name' => $user->name,
            'email' => $user->email,
            'roles' => $roles,
            'permissions' => $permissions,
        ];

        if (property_exists($user, 'is_owner') || method_exists($user, 'getAttribute')) {
            $payload['is_owner'] = (bool) (method_exists($user, 'getAttribute')
                ? $user->getAttribute('is_owner')
                : ($user->is_owner ?? false));
        }

        return array_merge($payload, $extra);
    }
}
