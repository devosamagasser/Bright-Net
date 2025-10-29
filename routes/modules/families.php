<?php

use Illuminate\Support\Facades\Route;
use App\Modules\Families\Presentation\Http\Controllers\FamilyController;

Route::prefix('families')
    ->group(function (): void {
        Route::get('subcategories/{subcategory}', [FamilyController::class, 'index'])
            ->whereNumber('subcategory');

        Route::post('/', [FamilyController::class, 'store']);
        Route::get('/{family}', [FamilyController::class, 'show'])
            ->whereNumber('family');
        Route::put('/{family}', [FamilyController::class, 'update'])
            ->whereNumber('family');
        Route::delete('/{family}', [FamilyController::class, 'destroy'])
            ->whereNumber('family');
    });
