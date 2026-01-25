<?php

use Illuminate\Support\Facades\Route;
use App\Modules\PriceRules\Presentation\Http\Controllers\{
    CurrencyTransformFactorController,
    PriceFactorController
};

Route::prefix('suppliers')
    ->middleware(['auth:sanctum', 'supplier'])
    ->group(function (): void {
        // Currency Transform Factors
        Route::prefix('currency-transform-factors')
            ->group(function (): void {
                Route::get('/', [CurrencyTransformFactorController::class, 'index']);
                Route::post('/', [CurrencyTransformFactorController::class, 'store']);
                Route::put('/{currencyTransformFactor}', [CurrencyTransformFactorController::class, 'update'])
                    ->whereNumber('currencyTransformFactor');
                Route::delete('/{currencyTransformFactor}', [CurrencyTransformFactorController::class, 'destroy'])
                    ->whereNumber('currencyTransformFactor');
            });

        // Price Factors
        Route::prefix('price-factors')
            ->group(function (): void {
                Route::post('/apply', [PriceFactorController::class, 'apply']);
                Route::get('/history', [PriceFactorController::class, 'history']);
                Route::post('/{factor}/revert', [PriceFactorController::class, 'revert'])
                    ->whereNumber('factor');
                Route::post('/{factor}/reapply', [PriceFactorController::class, 'reapply'])
                    ->whereNumber('factor');
                Route::get('/{factor}/products', [PriceFactorController::class, 'products'])
                    ->whereNumber('factor');
            });
    });

