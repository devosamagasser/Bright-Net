<?php

use App\Modules\DataSheets\Domain\Models\DataField;
use Illuminate\Support\Facades\Route;
use App\Modules\DataSheets\Presentation\Http\Controllers\{
    DataTemplateController,
};

Route::prefix('data-templates')
    ->group(function (): void {
        Route::get('subcategories/{subcategory}', [DataTemplateController::class, 'index']);
        Route::post('/', [DataTemplateController::class, 'store']);
        Route::get('/{dataTemplate}', [DataTemplateController::class, 'show']);
        Route::put('/{dataTemplate}', [DataTemplateController::class, 'update']);
        Route::delete('/{dataTemplate}', [DataTemplateController::class, 'destroy']);
        Route::get('random-filterable/{dataTemplate}', function ($dataTemplate) {
            DataField::query()
                ->where('data_template_id', $dataTemplate)
                ->inRandomOrder()
                ->get()
                ->each(function (DataField $field) {
                    $field->is_filterable = true;
                    $field->save();
                });
        })->whereNumber('dataTemplate');
    });

