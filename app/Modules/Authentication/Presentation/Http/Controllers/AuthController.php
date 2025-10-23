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
            $request->only(['email', 'password']),
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
        $token = $request->user()->currentAccessToken();

        if ($token !== null) {
            $token->delete();
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
