<?php

use Illuminate\Support\Facades\Route;
use App\Modules\Geography\Presentation\Http\Controllers\RegionController;

Route::prefix('regions')
    ->name('regions.')
    ->group(function (): void {
        Route::get('/', [RegionController::class, 'index'])->name('index');
        Route::post('/', [RegionController::class, 'store'])->name('store');
        Route::get('{region}', [RegionController::class, 'show'])
            ->whereNumber('region')
            ->name('show');
        Route::put('{region}', [RegionController::class, 'update'])
            ->whereNumber('region')
            ->name('update');
        Route::delete('{region}', [RegionController::class, 'destroy'])
            ->whereNumber('region')
            ->name('destroy');
    });
