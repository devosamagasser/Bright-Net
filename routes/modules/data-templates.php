<?php

use Illuminate\Support\Facades\Route;
use App\Modules\DataSheets\Presentation\Http\Controllers\DataTemplateController;

Route::prefix('data-templates')
    ->name('data-templates.')
    ->group(function (): void {
        Route::post('/', [DataTemplateController::class, 'store'])->name('store');
    });
