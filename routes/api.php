<?php

use App\Modules\DataSheets\Domain\Models\DataField;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::get('nullable-all', function () {
    DataField::where('is_required', 1)->get()->each(function (DataField $field) {
        $field->is_required = 0;
        $field->save();
    });
});

