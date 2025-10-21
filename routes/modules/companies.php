<?php

use Illuminate\Support\Facades\Route;
use App\Modules\Companies\Presentation\Http\Controllers\CompanyController;

Route::prefix('companies')
    ->name('companies.')
    ->group(function (): void {
        Route::get('/', [CompanyController::class, 'index'])->name('index');
        Route::post('/', [CompanyController::class, 'store'])->name('store');
        Route::get('{company}', [CompanyController::class, 'show'])
            ->whereNumber('company')
            ->name('show');
        Route::put('{company}', [CompanyController::class, 'update'])
            ->whereNumber('company')
            ->name('update');
        Route::delete('{company}', [CompanyController::class, 'destroy'])
            ->whereNumber('company')
            ->name('destroy');
    });
