<?php

namespace App\Modules\AccessControl\Presentation\Http\Controllers;

use App\Modules\AccessControl\Application\Exceptions\InvalidCredentialsException;
use App\Modules\AccessControl\Application\UseCases\{
    GetAuthenticatedUserUseCase,
    LoginCompanyUserUseCase,
    LogoutUserUseCase
};
use App\Modules\AccessControl\Presentation\Http\Requests\LoginRequest;
use App\Modules\AccessControl\Presentation\Http\Transformers\AuthenticatedUserPayloadFactory;
use App\Modules\Companies\Domain\ValueObjects\CompanyType;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

use function abort_if;

final class SupplierAuthController
{
    public function __construct(
        private readonly LoginCompanyUserUseCase $loginUseCase,
        private readonly LogoutUserUseCase $logoutUseCase,
        private readonly GetAuthenticatedUserUseCase $authenticatedUserUseCase,
        private readonly AuthenticatedUserPayloadFactory $payloadFactory,
    ) {
    }

    public function login(LoginRequest $request): JsonResponse
    {
        try {
            $result = $this->loginUseCase->handle($request->toLoginData('supplier-api'), CompanyType::SUPPLIER);
        } catch (InvalidCredentialsException) {
            throw ValidationException::withMessages([
                'email' => [__('auth.failed')],
            ]);
        }

        return response()->json([
            'token' => $result->token->plainTextToken,
            'token_type' => 'Bearer',
            'abilities' => $result->token->abilities,
            'user' => $this->payloadFactory->make($result->user, $result->extraUserData),
        ]);
    }

    public function logout(Request $request): JsonResponse
    {
        $this->logoutUseCase->handle($request->user('company'));

        return response()->json([
            'message' => 'Logged out successfully.',
        ]);
    }

    public function me(): JsonResponse
    {
        $user = $this->authenticatedUserUseCase->handle('company', ['company']);

        abort_if($user === null, 401);

        return response()->json([
            'user' => $this->payloadFactory->make($user, [
                'company' => $user->company ? [
                    'id' => $user->company->getKey(),
                    'name' => $user->company->name,
                    'type' => $user->company->type->value,
                ] : null,
            ]),
        ]);
    }
}
