<?php

namespace App\Modules\AccessControl\Presentation\Http\Controllers;

use App\Modules\AccessControl\Application\Exceptions\InvalidCredentialsException;
use App\Modules\AccessControl\Application\UseCases\{
    GetAuthenticatedUserUseCase,
    LoginPlatformUserUseCase,
    LogoutUserUseCase
};
use App\Modules\AccessControl\Presentation\Http\Requests\LoginRequest;
use App\Modules\AccessControl\Presentation\Http\Transformers\AuthenticatedUserPayloadFactory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

use function abort_if;

final class PlatformAuthController
{
    public function __construct(
        private readonly LoginPlatformUserUseCase $loginUseCase,
        private readonly LogoutUserUseCase $logoutUseCase,
        private readonly GetAuthenticatedUserUseCase $authenticatedUserUseCase,
        private readonly AuthenticatedUserPayloadFactory $payloadFactory,
    ) {
    }

    public function login(LoginRequest $request): JsonResponse
    {
        try {
            $result = $this->loginUseCase->handle($request->toLoginData('platform-api'));
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
        $this->logoutUseCase->handle($request->user('platform'));

        return response()->json([
            'message' => 'Logged out successfully.',
        ]);
    }

    public function me(): JsonResponse
    {
        $user = $this->authenticatedUserUseCase->handle('platform');

        abort_if($user === null, 401);

        return response()->json([
            'user' => $this->payloadFactory->make($user),
        ]);
    }
}
