<?php

use Illuminate\Support\Facades\Route;
use App\Modules\Families\Presentation\Http\Controllers\FamilyController;

Route::prefix('families')
    ->group(function (): void {
        Route::get('subcategories/{subcategory}', [FamilyController::class, 'index']);
        Route::post('/', [FamilyController::class, 'store']);
        Route::get('/{family}', [FamilyController::class, 'show']);
        Route::put('/{family}', [FamilyController::class, 'update']);
        Route::delete('/{family}', [FamilyController::class, 'destroy']);
    });
