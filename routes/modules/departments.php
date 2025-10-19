<?php

use Illuminate\Support\Facades\Route;
use App\Modules\Departments\Presentation\Http\Controllers\DepartmentController;

Route::prefix('departments')
    ->name('departments.')
    ->group(function (): void {
        Route::get('/', [DepartmentController::class, 'index'])->name('index');
        Route::post('/', [DepartmentController::class, 'store'])->name('store');
        Route::get('{department}', [DepartmentController::class, 'show'])
            ->whereNumber('department')
            ->name('show');
        Route::put('{department}', [DepartmentController::class, 'update'])
            ->whereNumber('department')
            ->name('update');
        Route::delete('{department}', [DepartmentController::class, 'destroy'])
            ->whereNumber('department')
            ->name('destroy');
    });
