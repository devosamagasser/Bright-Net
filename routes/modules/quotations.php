<?php

use Illuminate\Support\Facades\Route;
use App\Modules\Quotations\Presentation\Http\Controllers\{
    QuotationDraftController,
    QuotationItemAccessoryController,
    QuotationItemController,
    QuotationController,
};
use App\Modules\QuotationLogs\Presentation\Http\Controllers\QuotationLogsController;

Route::prefix('quotations')
    ->middleware(['auth:sanctum', 'supplier'])
    ->group(function (): void {
        Route::get('draft', [QuotationController::class, 'show']);

        Route::patch('draft', [QuotationController::class, 'update']);

        Route::patch('{quotation}/flags', [QuotationController::class, 'updateFlags'])
            ->whereNumber('quotation');

        Route::patch('{quotation}/details', [QuotationController::class, 'updateDetails'])
            ->whereNumber('quotation');

        Route::post('draft/items', [QuotationItemController::class, 'storeItem']);

        Route::patch('items/{item}', [QuotationItemController::class, 'update'])
            ->whereNumber('item');

        Route::post('items/{item}/replace', [QuotationItemController::class, 'replace'])
            ->whereNumber('item');

        Route::delete('items/{item}', [QuotationItemController::class, 'destroy'])
            ->whereNumber('item');

        Route::post('items/{item}/accessories', [QuotationItemAccessoryController::class, 'store'])
            ->whereNumber('item');

        Route::patch('accessories/{accessory}', [QuotationItemAccessoryController::class, 'update'])
            ->whereNumber('accessory');

        Route::delete('accessories/{accessory}', [QuotationItemAccessoryController::class, 'destroy'])
            ->whereNumber('accessory');

        Route::get('/{quotation}/undo', [QuotationLogsController::class, 'undo'])
            ->whereNumber('quotation');

        Route::get('/{quotation}/redo', [QuotationLogsController::class, 'redo'])
            ->whereNumber('quotation');
    });
