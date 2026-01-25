<?php

use App\Modules\Departments\Presentation\Http\Controllers\DepartmentController;
use App\Modules\SolutionsCatalog\Presentation\Http\Controllers\SolutionBrandController;
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
        Route::get('{solutionId}/departments', [DepartmentController::class, 'index'])->name('departments.index');

        Route::get('{solution}/brands', [SolutionBrandController::class, 'index'])
            ->whereNumber('solution')
            ->name('brands.index');

        Route::get('{solution}/brands/{brand}', [SolutionBrandController::class, 'show'])
            ->whereNumber('solution')
            ->whereNumber('brand')
            ->name('brands.show');

    });
