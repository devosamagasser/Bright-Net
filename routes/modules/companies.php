<?php

use Illuminate\Support\Facades\Route;
use App\Modules\Companies\Presentation\Http\Controllers\CompanyController;
use App\Modules\Companies\Presentation\Http\Controllers\SupplierCompanyController;

Route::prefix('companies')->name('companies.')->group(function (): void {
    Route::delete('{company}', [CompanyController::class, 'destroy'])
        ->whereNumber('company')
        ->name('destroy');

    Route::prefix('suppliers')->name('suppliers.')->group(function (): void {
        Route::get('/', [SupplierCompanyController::class, 'index'])->name('index');
        Route::post('/', [SupplierCompanyController::class, 'store'])->name('store');
        Route::get('{company}', [SupplierCompanyController::class, 'show'])
            ->whereNumber('company')
            ->name('show');
        Route::put('{company}', [SupplierCompanyController::class, 'update'])
            ->whereNumber('company')
            ->name('update');
        Route::delete('{company}', [SupplierCompanyController::class, 'destroy'])
            ->whereNumber('company')
            ->name('destroy');
    });
});
