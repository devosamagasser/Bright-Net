<?php

use Illuminate\Support\Facades\Route;
use App\Modules\Products\Presentation\Http\Controllers\ProductController;

Route::prefix('products')
    ->group(function (): void {
        Route::get('families/{family}', [ProductController::class, 'index'])
            ->whereNumber('family');

        Route::post('/', [ProductController::class, 'store']);
        Route::get('/{product}', [ProductController::class, 'show'])
            ->whereNumber('product');
        Route::put('/{product}', [ProductController::class, 'update'])
            ->whereNumber('product');
        Route::delete('/{product}', [ProductController::class, 'destroy'])
            ->whereNumber('product');
    });
