<?php

use App\Modules\Authentication\Domain\ValueObjects\UserType;
use App\Modules\Authentication\Presentation\Http\Controllers\AuthController;

Route::prefix('auth/{type}')
    ->whereIn('type', UserType::cases())
    ->group(function (): void {
        Route::post('login', [AuthController::class, 'login']);

        Route::middleware('auth:sanctum')->group(function (): void {
            Route::delete('logout', [AuthController::class, 'logout']);
            Route::get('me', [AuthController::class, 'me']);
        });
    });
