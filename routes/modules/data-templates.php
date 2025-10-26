<?php

use Illuminate\Support\Facades\Route;
use App\Modules\DataSheets\Presentation\Http\Controllers\DataTemplateController;

Route::prefix('data-templates')
    ->name('data-templates.')
    ->group(function (): void {
        Route::get('/', [DataTemplateController::class, 'index'])->name('index');
        Route::post('/', [DataTemplateController::class, 'store'])->name('store');
        Route::get('/{dataTemplate}', [DataTemplateController::class, 'show'])->name('show');
        Route::put('/{dataTemplate}', [DataTemplateController::class, 'update'])->name('update');
        Route::delete('/{dataTemplate}', [DataTemplateController::class, 'destroy'])->name('destroy');
    });
