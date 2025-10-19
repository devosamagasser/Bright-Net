<?php

use App\Modules\SolutionsCatalog\Presentation\Http\Controllers\SolutionController;
use Illuminate\Support\Facades\Route;

Route::prefix('solutions')
    ->name('solutions.')
    ->group(function (): void {
        Route::get('/', [SolutionController::class, 'index'])->name('index');
        Route::post('/', [SolutionController::class, 'store'])->name('store');
        Route::get('{solution}', [SolutionController::class, 'show'])
            ->whereNumber('solution')
            ->name('show');
        Route::put('{solution}', [SolutionController::class, 'update'])
            ->whereNumber('solution')
            ->name('update');
        Route::delete('{solution}', [SolutionController::class, 'destroy'])
            ->whereNumber('solution')
            ->name('destroy');
    });
