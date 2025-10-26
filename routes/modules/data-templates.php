<?php

use Illuminate\Support\Facades\Route;
use App\Modules\DataSheets\Domain\ValueObjects\DataTemplateType;
use App\Modules\DataSheets\Presentation\Http\Controllers\{
    DataTemplateController,
    FamilyDataTemplateController,
    ProductDataTemplateController,
};

Route::prefix('data-templates')
    ->group(function (): void {
        Route::get('/', [DataTemplateController::class, 'index']);
        Route::post('/', [DataTemplateController::class, 'store']);
        Route::get('/{dataTemplate}', [DataTemplateController::class, 'show']);
        Route::put('/{dataTemplate}', [DataTemplateController::class, 'update']);
        Route::delete('/{dataTemplate}', [DataTemplateController::class, 'destroy']);
    });

Route::prefix('family-data-templates')
    ->name('family-data-templates.')
    ->defaults('data_template_type', DataTemplateType::FAMILY->value)
    ->group(function (): void {
        Route::get('/', [FamilyDataTemplateController::class, 'index'])->name('index');
        Route::post('/', [FamilyDataTemplateController::class, 'store'])->name('store');
        Route::get('/{dataTemplate}', [FamilyDataTemplateController::class, 'show'])->name('show');
        Route::put('/{dataTemplate}', [FamilyDataTemplateController::class, 'update'])->name('update');
        Route::delete('/{dataTemplate}', [FamilyDataTemplateController::class, 'destroy'])->name('destroy');
    });

Route::prefix('product-data-templates')
    ->name('product-data-templates.')
    ->defaults('data_template_type', DataTemplateType::PRODUCT->value)
    ->group(function (): void {
        Route::get('/', [ProductDataTemplateController::class, 'index'])->name('index');
        Route::post('/', [ProductDataTemplateController::class, 'store'])->name('store');
        Route::get('/{dataTemplate}', [ProductDataTemplateController::class, 'show'])->name('show');
        Route::put('/{dataTemplate}', [ProductDataTemplateController::class, 'update'])->name('update');
        Route::delete('/{dataTemplate}', [ProductDataTemplateController::class, 'destroy'])->name('destroy');
    });
