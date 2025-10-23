<?php

namespace App\Modules\Authentication\Domain\Types;

use App\Models\CompanyUser;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Support\Facades\Hash;
use App\Modules\Authentication\Domain\ValueObjects\UserType;
use App\Modules\Authentication\Domain\Types\UserTypeInterface;

class UserCompany implements UserTypeInterface
{
    public function type(): UserType
    {
        return UserType::COMPANY;
    }

    public function relations(): array
    {
        return ['company'];
    }

    public function serialize($user): array
    {
        $user->load($this->relations());
        return [
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'company' => [
                'id' => $user->company->id,
                'name' => $user->company->name,
            ],
            'role' => $user->getRoleNames()->first(),
            'permissions' => $user->getAllPermissions()->pluck('name')->toArray(),
        ];
    }

    public function generateToken($user)
    {
        return $user->createToken('user-company')->plainTextToken;
    }

    public function checkCredentials($credentials)
    {
        $user = CompanyUser::where('email', $credentials['email'])
            ->first();

        if (! $user || ! Hash::check($credentials['password'], $user->password)) {
            throw new AuthenticationException(__('auth.invalid_credentials'));
        }

        return $user;
    }
}
