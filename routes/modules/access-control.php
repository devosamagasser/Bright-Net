<?php

use App\Modules\AccessControl\Presentation\Http\Controllers\{
    PlatformAuthController,
    SupplierAuthController
};
use Illuminate\Support\Facades\Route;

Route::prefix('platform')->group(function (): void {
    Route::post('login', [PlatformAuthController::class, 'login']);

    Route::middleware('auth:platform')->group(function (): void {
        Route::post('logout', [PlatformAuthController::class, 'logout']);
        Route::get('me', [PlatformAuthController::class, 'me']);
    });
});

Route::prefix('supplier')->group(function (): void {
    Route::post('login', [SupplierAuthController::class, 'login']);

    Route::middleware('auth:company')->group(function (): void {
        Route::post('logout', [SupplierAuthController::class, 'logout']);
        Route::get('me', [SupplierAuthController::class, 'me']);
    });
});
