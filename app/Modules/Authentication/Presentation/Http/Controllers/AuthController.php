<?php

namespace App\Modules\Authentication\Presentation\Http\Controllers;

use Illuminate\Http\Request;
use App\Modules\Shared\Support\Helper\ApiResponse;
use App\Modules\Authentication\Application\DTOs\AuthInput;
use App\Modules\Authentication\Application\UseCases\LoginInUseCase;
use App\Modules\Authentication\Application\UseCases\GetUserDataUseCase;
use App\Modules\Authentication\Presentation\Http\Requests\LoginRequest;

class AuthController
{

    public function __construct(
        private readonly LoginInUseCase $loginInUseCase,
        private readonly GetUserDataUseCase $getUserDataUseCase,
    ) {
    }
    public function login(LoginRequest $request, string $type)
    {
        $input = AuthInput::fromArray(
            $request->validated(),
            $type
        );

        $authData = $this->loginInUseCase->handle($input);

        return ApiResponse::success(
            $authData,
            __('auth.login_success')
        );
    }

    public function logout(Request $request)
    {
        $user = $request->user();

        if ($user === null) {
            return ApiResponse::message(__('auth.logout_success'));
        }

        $token = $user->currentAccessToken();

        if ($token !== null) {
            $token->delete();

            return ApiResponse::message(__('auth.logout_success'));
        }

        $guardName = null;

        if (method_exists($user, 'getDefaultGuardName')) {
            $guardName = $user->getDefaultGuardName();
        }

        $guard = $guardName !== null ? auth($guardName) : auth();

        $guard->logout();

        if ($request->hasSession()) {
            $request->session()->invalidate();
            $request->session()->regenerateToken();
        }

        return ApiResponse::message(__('auth.logout_success'));
    }

    public function me()
    {
        $user = auth()->user();
        $authData = $this->getUserDataUseCase->handle($user);
        return ApiResponse::success($authData);
    }
}
