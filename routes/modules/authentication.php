<?php

use App\Modules\Authentication\Domain\ValueObjects\UserType;
use App\Modules\Authentication\Presentation\Http\Controllers\AuthController;

Route::prefix('auth')
    ->group(function (): void {
        Route::post('/{type}/login', [AuthController::class, 'login'])
            ->whereIn(
                'type',
                array_map(static fn (UserType $type) => $type->value, UserType::cases())
            );

        Route::middleware('auth:sanctum')->group(function (): void {
            Route::delete('logout', [AuthController::class, 'logout']);
            Route::get('me', [AuthController::class, 'me']);
        });
    });
