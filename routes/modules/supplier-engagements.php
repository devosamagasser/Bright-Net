<?php

use Illuminate\Support\Facades\Route;
use App\Modules\SupplierEngagements\Presentation\Http\Controllers\SupplierEngagementController;

Route::prefix('companies/suppliers/{company}/engagements')
    ->whereNumber('company')
    ->name('companies.suppliers.engagements.')
    ->group(function (): void {
        Route::get('solutions', [SupplierEngagementController::class, 'solutions'])
            ->name('solutions.index');

        Route::get('solutions/{supplierSolution}/brands', [SupplierEngagementController::class, 'brands'])
            ->whereNumber('supplierSolution')
            ->name('solutions.brands.index');

        Route::get('brands/{supplierBrand}/departments', [SupplierEngagementController::class, 'departments'])
            ->whereNumber('supplierBrand')
            ->name('brands.departments.index');

        Route::get('departments/{supplierDepartment}/subcategories', [SupplierEngagementController::class, 'subcategories'])
            ->whereNumber('supplierDepartment')
            ->name('departments.subcategories.index');
    });
