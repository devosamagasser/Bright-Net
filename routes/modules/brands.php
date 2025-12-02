<?php

use Illuminate\Support\Facades\Route;
use App\Modules\Brands\Presentation\Http\Controllers\BrandController;

Route::prefix('brands')
    ->name('brands.')
    ->group(function (): void {
        Route::get('/', [BrandController::class, 'index'])->name('index');
        Route::post('/', [BrandController::class, 'store'])->name('store');
        Route::get('{brand}', [BrandController::class, 'show'])
            ->whereNumber('brand')
            ->name('show');
        Route::put('{brand}', [BrandController::class, 'update'])
            ->whereNumber('brand')
            ->name('update');
        Route::patch('{brand}', [BrandController::class, 'updateAvatar'])
            ->whereNumber('brand')
            ->name('updateAvatar');
        Route::delete('{brand}', [BrandController::class, 'destroy'])
            ->whereNumber('brand')
            ->name('destroy');
    });
