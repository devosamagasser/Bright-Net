<?php

use Illuminate\Support\Facades\Route;
use App\Modules\Quotations\Presentation\Http\Controllers\{
    QuotationDraftController,
    QuotationItemAccessoryController,
    QuotationItemController,
};

Route::prefix('quotations')
    ->middleware('auth:sanctum')
    ->group(function (): void {
        Route::get('draft', [QuotationDraftController::class, 'show']);
        Route::patch('draft', [QuotationDraftController::class, 'update']);
        Route::post('draft/items', [QuotationDraftController::class, 'storeItem']);

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
    });
