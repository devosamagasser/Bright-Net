<?php

use Illuminate\Support\Facades\Route;
use App\Modules\SupplierEngagements\Presentation\Http\Controllers\SupplierEngagementController;

Route::prefix('suppliers/{company}')
    ->whereNumber('company')
    ->name('suppliers.')
    ->group(function (): void {
        Route::get('solutions', [SupplierEngagementController::class, 'solutions'])
            ->name('solutions.index');

        Route::get('solutions/{supplierSolution}', [SupplierEngagementController::class, 'brands'])
            ->whereNumber('supplierSolution')
            ->name('solutions.brands.index');

        Route::get('brands/{supplierBrand}', [SupplierEngagementController::class, 'departments'])
            ->whereNumber('supplierBrand')
            ->name('brands.departments.index');

        Route::get('departments/{supplierDepartment}', [SupplierEngagementController::class, 'subcategories'])
            ->whereNumber('supplierDepartment')
            ->name('departments.subcategories.index');
    });
