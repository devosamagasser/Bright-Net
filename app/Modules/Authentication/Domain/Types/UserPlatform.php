<?php

namespace App\Modules\Authentication\Domain\Types;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use App\Modules\Authentication\Domain\ValueObjects\UserType;
use App\Modules\Authentication\Domain\Types\UserTypeInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class UserPlatform implements UserTypeInterface
{
    public function type(): UserType
    {
        return UserType::PLATFORM;
    }

    public function relations(): array
    {
        return [];
    }

    public function serialize($user): array
    {
        $user->load($this->relations());
        return [
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'role' => $user->getRoleNames()->first(),
            'permissions' => $user->getAllPermissions()->pluck('name')->toArray(),
        ];
    }

    public function generateToken($user)
    {
        return $user->createToken('user-platform')->plainTextToken;
    }

    public function checkCredentials($credentials)
    {
        $user = User::where('email', $credentials['email'])
            ->firstOrFail();
        if (!Hash::check($credentials['password'], $user->password))
            throw new NotFoundHttpException('Invalid credentials.');
        return $user;
    }
}
