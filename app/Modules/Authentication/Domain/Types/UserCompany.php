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
        $user->loadMissing($this->relations());

        $company = $user->company;

        $companyPayload = null;

        if ($company !== null) {
            $companyPayload = [
                'id' => $company->id,
                'name' => $company->name,
            ];
        }

        return [
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'company' => $companyPayload,
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
