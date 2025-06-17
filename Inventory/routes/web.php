<?php

use App\Http\Controllers\ItemController;
use Illuminate\Support\Facades\Route;



Route::get('/', function () {
    return redirect('/items');
});
// Your existing routes
Route::get('/items', [ItemController::class, 'index'])->name('items.index');
Route::post('/items', [ItemController::class, 'store'])->name('items.store');
Route::put('/items/{item}', [ItemController::class, 'update'])->name('items.update');
Route::delete('/items/{item}', [ItemController::class, 'destroy'])->name('items.destroy');

// New CSV export routes
Route::get('/items/export/csv', [ItemController::class, 'exportCsv'])->name('items.export.csv');
Route::get('/items/export/csv-formatted', [ItemController::class, 'exportCsvFormatted'])->name('items.export.csv.formatted');
Route::get('/items/export/requisition-slip', [ItemController::class, 'exportRequisitionSlip'])->name('items.export.requisition.slip');