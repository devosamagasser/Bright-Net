<?php

use Illuminate\Support\Facades\Route;
use App\Modules\DataSheets\Domain\ValueObjects\DataTemplateType;
use App\Modules\Families\Presentation\Http\Controllers\FamilyController;
use App\Modules\Products\Presentation\Http\Controllers\ProductController;
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

        Route::get('families/{family}/products', [ProductController::class, 'index'])
            ->whereNumber('family');

        Route::prefix('families')
            ->group(function (): void {
                Route::post('/', [FamilyController::class, 'store']);
                Route::get('/{family}', [FamilyController::class, 'show']);
                Route::get('/download/template', [FamilyController::class, 'export']);
                Route::put('/{family}', [FamilyController::class, 'update']);
                Route::delete('/{family}', [FamilyController::class, 'destroy']);
                Route::post('/upload/{subcategory}', [FamilyController::class, 'import'])
                    ->whereNumber('subcategory');

            });


        Route::prefix('products')
            ->group(function (): void {
                Route::post('/', [ProductController::class, 'store']);
                Route::get('/{product}', [ProductController::class, 'show'])
                    ->whereNumber('product');
                Route::get('/{product}/data-sheet', [ProductController::class, 'showDataSheet'])
                    ->whereNumber('product');
                Route::get('/download/{family}/template', [ProductController::class, 'export'])
                    ->whereNumber('family');
                Route::put('/{product}', [ProductController::class, 'update'])
                    ->whereNumber('product');
                Route::delete('/{product}', [ProductController::class, 'destroy'])
                    ->whereNumber('product');
                Route::patch('/cut/{product}/paste/', [ProductController::class, 'paste'])
                    ->whereNumber('product');
                Route::post('/upload/{family}', [ProductController::class, 'import'])
                    ->whereNumber('family');
                });
            });

