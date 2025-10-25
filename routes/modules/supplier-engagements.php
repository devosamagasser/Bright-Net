<?php

use Illuminate\Support\Facades\Route;
use App\Modules\SupplierEngagements\Presentation\Http\Controllers\SupplierEngagementController;


Route::prefix('suppliers/{company}')
    ->whereNumber('company')
    ->group(function (): void {
        Route::get('solutions', [SupplierEngagementController::class, 'solutions']);

        Route::get('solutions/{supplierSolution}', [SupplierEngagementController::class, 'brands'])
            ->whereNumber('supplierSolution');

        Route::get('brands/{supplierBrand}', [SupplierEngagementController::class, 'departments'])
            ->whereNumber('supplierBrand');

        Route::get('departments/{supplierDepartment}', [SupplierEngagementController::class, 'subcategories'])
            ->whereNumber('supplierDepartment');
});


Route::prefix('suppliers')
    ->middleware('auth:sanctum')
    ->group(function (): void {
        Route::get('solutions', [SupplierEngagementController::class, 'solutions']);

        Route::get('solutions/{supplierSolution}', [SupplierEngagementController::class, 'brands'])
            ->whereNumber('supplierSolution');

        Route::get('brands/{supplierBrand}', [SupplierEngagementController::class, 'departments'])
            ->whereNumber('supplierBrand');

        Route::get('departments/{supplierDepartment}', [SupplierEngagementController::class, 'subcategories'])
            ->whereNumber('supplierDepartment');
});
