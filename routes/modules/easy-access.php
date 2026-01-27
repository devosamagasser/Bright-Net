<?php

use App\Modules\Shared\Presentation\Http\Controllers\EasyAccessController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Easy Access Routes
|--------------------------------------------------------------------------
|
| Lightweight endpoints for dropdown/filter data.
| All responses return minimal data: id and name only.
|
*/

// Currencies (no authentication needed for static data)
Route::get('/currencies', [EasyAccessController::class, 'currencies']);

// Solutions for the authenticated supplier
Route::get('/solutions', [EasyAccessController::class, 'solutions']);

// Brands for a specific supplier solution
Route::get('/solutions/{supplierSolution}/brands', [EasyAccessController::class, 'brands'])
    ->whereNumber('supplierSolution');

// Departments (categories) for a specific supplier brand
Route::get('/brands/{supplierBrand}/departments', [EasyAccessController::class, 'departments'])
    ->whereNumber('supplierBrand');

// Subcategories for a specific supplier department
Route::get('/departments/{supplierDepartment}/subcategories', [EasyAccessController::class, 'subcategories'])
    ->whereNumber('supplierDepartment');

// Families for a specific subcategory and supplier department
Route::get('departments/{supplierDepartment}/subcategories/{subcategory}/families', [EasyAccessController::class, 'families'])
    ->whereNumber('subcategory')
    ->whereNumber('supplierDepartment');

// Unique origins from products (for the authenticated supplier)
Route::get('/origins', [EasyAccessController::class, 'origins']);
