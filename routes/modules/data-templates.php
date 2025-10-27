<?php

use Illuminate\Support\Facades\Route;
use App\Modules\DataSheets\Domain\ValueObjects\DataTemplateType;
use App\Modules\DataSheets\Presentation\Http\Controllers\{
    DataTemplateController,
    FamilyDataTemplateController,
    ProductDataTemplateController,
    SubcategoryDataTemplateController,
    SupplierDataTemplateController,
};

Route::prefix('data-templates')
    ->group(function (): void {
        Route::get('subcategories/{subcategory}', [DataTemplateController::class, 'index']);
        Route::post('/', [DataTemplateController::class, 'store']);
        Route::get('/{dataTemplate}', [DataTemplateController::class, 'show']);
        Route::put('/{dataTemplate}', [DataTemplateController::class, 'update']);
        Route::delete('/{dataTemplate}', [DataTemplateController::class, 'destroy']);
    });

