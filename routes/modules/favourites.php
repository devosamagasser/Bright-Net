<?php

use App\Modules\Favourites\Presentation\Http\Controllers\CollectionController;
use App\Modules\Favourites\Presentation\Http\Controllers\CollectionProductController;
use Illuminate\Support\Facades\Route;

Route::prefix('favourites')
    ->name('favourites.')
    ->middleware('auth:sanctum')
    ->group(function (): void {
        // Collection CRUD
        Route::get('/', [CollectionController::class, 'index'])->name('index');
        Route::post('/', [CollectionController::class, 'store'])->name('store');
        Route::get('{collection}', [CollectionController::class, 'show'])
            ->whereNumber('collection')
            ->name('show');
        Route::put('{collection}', [CollectionController::class, 'update'])
            ->whereNumber('collection')
            ->name('update');
        Route::delete('{collection}', [CollectionController::class, 'destroy'])
            ->whereNumber('collection')
            ->name('destroy');

        // Collection Products
        Route::post('{collection}/products', [CollectionProductController::class, 'store'])
            ->whereNumber('collection')
            ->name('products.store');
        Route::delete('{collection}/products/{product}', [CollectionProductController::class, 'destroy'])
            ->whereNumber('collection')
            ->whereNumber('product')
            ->name('products.destroy');
    });

