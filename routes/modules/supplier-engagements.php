<?php

use Illuminate\Support\Facades\Route;
use App\Modules\DataSheets\Domain\ValueObjects\DataTemplateType;
use App\Modules\Families\Presentation\Http\Controllers\FamilyController;
use App\Modules\DataSheets\Presentation\Http\Controllers\SupplierDataTemplateController;
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

        Route::get('subcategories/{subcategory}/data-templates/{type}', [SupplierDataTemplateController::class, 'show'])
            ->whereNumber('subcategory')
            ->whereIn('type', DataTemplateType::values());

        Route::get('subcategories/{subcategory}', [FamilyController::class, 'index'])
            ->whereNumber('subcategory');
            
        Route::prefix('families')
            ->group(function (): void {
                Route::post('/', [FamilyController::class, 'store']);
                Route::get('/{family}', [FamilyController::class, 'show']);
                Route::put('/{family}', [FamilyController::class, 'update']);
                Route::delete('/{family}', [FamilyController::class, 'destroy']);
            });
});

