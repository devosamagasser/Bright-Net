<?php

namespace App\Modules\Authentication\Application\UseCases;

use App\Modules\Authentication\Domain\Types\UserTypeFactory;
use App\Modules\Authentication\Domain\ValueObjects\UserType;
use App\Modules\Authentication\Application\DTOs\AuthData;
use App\Modules\Authentication\Application\DTOs\AuthInput;

class LoginInUseCase
{
    public function __construct(
        private readonly UserTypeFactory $userTypeFactory,
    ) {
    }

    public function handle(AuthInput $input): AuthData
    {
        $userType = $this->userTypeFactory->make(UserType::from($input->type));
        $user = $userType->checkCredentials($input->credentials);
        $token = $userType->generateToken($user);
        return AuthData::fromModel(
            userData: $userType->serialize($user),
            token: $token,
        );
    }
}
