<?php

namespace App\Modules\AccessControl\Application\UseCases;

use App\Modules\AccessControl\Application\Contracts\TokenIssuerInterface;
use App\Modules\AccessControl\Application\DTOs\{LoginData, LoginResult};
use App\Modules\AccessControl\Application\Exceptions\InvalidCredentialsException;
use App\Modules\AccessControl\Domain\Repositories\CompanyUserRepositoryInterface;
use App\Modules\Companies\Domain\ValueObjects\CompanyType;
use Illuminate\Contracts\Hashing\Hasher;

final class LoginCompanyUserUseCase
{
    public function __construct(
        private readonly CompanyUserRepositoryInterface $users,
        private readonly TokenIssuerInterface $tokens,
        private readonly Hasher $hasher,
    ) {
    }

    public function handle(LoginData $data, CompanyType $companyType): LoginResult
    {
        $user = $this->users->findByEmailAndCompanyType($data->email, $companyType);

        if (! $user || ! $this->hasher->check($data->password, $user->getAuthPassword())) {
            throw new InvalidCredentialsException();
        }

        $token = $this->tokens->issueToken($user, $data->deviceName);
        $company = $user->company;

        return new LoginResult($user, $token, [
            'is_owner' => (bool) $user->is_owner,
            'company' => $company ? [
                'id' => $company->getKey(),
                'name' => $company->name,
                'type' => $company->type->value,
            ] : null,
        ]);
    }
}
