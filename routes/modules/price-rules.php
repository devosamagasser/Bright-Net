<?php

use Illuminate\Support\Facades\Route;
use App\Modules\PriceRules\Presentation\Http\Controllers\{
    CurrencyTransformFactorController,
    PriceFactorController
};
use \App\Modules\PriceRules\Domain\ValueObjects\PriceCurrency;
use \App\Modules\Shared\Support\Helper\ApiResponse;

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
                Route::post('/flatten', [PriceFactorController::class, 'flatten']);
                Route::post('/{factor}/restore', [PriceFactorController::class, 'restore'])
                    ->whereNumber('factor');
                Route::get('/{factor}/products', [PriceFactorController::class, 'products'])
                    ->whereNumber('factor');
            });
    });

