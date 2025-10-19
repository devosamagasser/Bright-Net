<?php

use Illuminate\Support\Facades\Route;
use App\Modules\Taxonomy\Presentation\Http\Controllers\ColorController;

Route::prefix('colors')
    ->name('colors.')
    ->group(function (): void {
        Route::get('/', [ColorController::class, 'index'])->name('index');
        Route::post('/', [ColorController::class, 'store'])->name('store');
        Route::get('{color}', [ColorController::class, 'show'])
            ->whereNumber('color')
            ->name('show');
        Route::put('{color}', [ColorController::class, 'update'])
            ->whereNumber('color')
            ->name('update');
        Route::delete('{color}', [ColorController::class, 'destroy'])
            ->whereNumber('color')
            ->name('destroy');
    });
