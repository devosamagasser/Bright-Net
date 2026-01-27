<?php

use App\Modules\Departments\Presentation\Http\Controllers\DepartmentController;
use App\Modules\SolutionsCatalog\Presentation\Http\Controllers\SolutionBrandController;
use App\Modules\SolutionsCatalog\Presentation\Http\Controllers\SolutionController;
use Illuminate\Support\Facades\Route;

Route::prefix('solutions')
        ->group(function (): void {
        Route::get('/', [SolutionController::class, 'index']);
        Route::post('/', [SolutionController::class, 'store']);
        Route::get('{solution}', [SolutionController::class, 'show'])
            ->whereNumber('solution');
        Route::put('{solution}', [SolutionController::class, 'update'])
            ->whereNumber('solution');
        Route::delete('{solution}', [SolutionController::class, 'destroy'])
            ->whereNumber('solution');
        Route::get('{solutionId}/departments', [DepartmentController::class, 'index']);

        Route::get('{solution}/brands', [SolutionBrandController::class, 'index'])
            ->whereNumber('solution');

        Route::get('{solution}/brands/{brand}', [SolutionBrandController::class, 'show'])
            ->whereNumber('solution')
            ->whereNumber('brand');
    });
