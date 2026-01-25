<?php

use Illuminate\Support\Facades\Route;
use App\Modules\Specifications\Presentation\Http\Controllers\{
    SpecificationItemAccessoryController,
    SpecificationItemController,
    SpecificationController,
};
use App\Modules\SpecificationLogs\Presentation\Http\Controllers\SpecificationLogsController;

Route::prefix('specifications')
    ->middleware(['auth:sanctum', 'supplier'])
    ->group(function (): void {
        Route::get('draft', [SpecificationController::class, 'show']);

        Route::patch('{specification}/update', [SpecificationController::class, 'update'])
            ->whereNumber('specification');

        Route::post('draft/items', [SpecificationItemController::class, 'storeItem']);

        Route::patch('items/{item}', [SpecificationItemController::class, 'update'])
            ->whereNumber('item');

        Route::post('items/{item}/replace', [SpecificationItemController::class, 'replace'])
            ->whereNumber('item');

        Route::delete('items/{item}', [SpecificationItemController::class, 'destroy'])
            ->whereNumber('item');

        Route::post('items/{item}/accessories', [SpecificationItemAccessoryController::class, 'store'])
            ->whereNumber('item');

        Route::patch('accessories/{accessory}', [SpecificationItemAccessoryController::class, 'update'])
            ->whereNumber('accessory');

        Route::delete('accessories/{accessory}', [SpecificationItemAccessoryController::class, 'destroy'])
            ->whereNumber('accessory');

        Route::get('/{specification}/undo', [SpecificationLogsController::class, 'undo'])
            ->whereNumber('specification');

        Route::get('/{specification}/redo', [SpecificationLogsController::class, 'redo'])
            ->whereNumber('specification');
    });


