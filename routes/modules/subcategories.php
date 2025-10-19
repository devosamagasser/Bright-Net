<?php

use Illuminate\Support\Facades\Route;
use App\Modules\Subcategories\Presentation\Http\Controllers\SubcategoryController;

Route::prefix('subcategories')
    ->name('subcategories.')
    ->group(function (): void {
        Route::get('/', [SubcategoryController::class, 'index'])->name('index');
        Route::post('/', [SubcategoryController::class, 'store'])->name('store');
        Route::get('{subcategory}', [SubcategoryController::class, 'show'])
            ->whereNumber('subcategory')
            ->name('show');
        Route::put('{subcategory}', [SubcategoryController::class, 'update'])
            ->whereNumber('subcategory')
            ->name('update');
        Route::delete('{subcategory}', [SubcategoryController::class, 'destroy'])
            ->whereNumber('subcategory')
            ->name('destroy');
    });
