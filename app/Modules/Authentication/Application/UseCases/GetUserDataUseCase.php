<?php

namespace App\Modules\Authentication\Application\UseCases;

use App\Modules\Authentication\Domain\Types\UserTypeFactory;
use App\Modules\Authentication\Domain\ValueObjects\UserType;
use App\Modules\Authentication\Application\DTOs\AuthData;
use Illuminate\Database\Eloquent\Model;

class GetUserDataUseCase
{
    public function __construct(
        private readonly UserTypeFactory $userTypeFactory,
    ) {
    }

    public function handle(Model $user): AuthData
    {
        $userType = $this->userTypeFactory->make(UserType::fromModel($user));
        return AuthData::fromModel(
            userData: $userType->serialize($user)
        );
    }
}
