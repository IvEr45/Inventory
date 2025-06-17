<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ItemController;

// Redirect the root (/) to the items page
Route::get('/', function () {
    return redirect('/items');
});

// Resourceful CRUD routes for items
Route::get('/items', [ItemController::class, 'index'])->name('items.index');
Route::post('/items', [ItemController::class, 'store'])->name('items.store');
Route::put('/items/{item}', [ItemController::class, 'update'])->name('items.update');
Route::delete('/items/{item}', [ItemController::class, 'destroy'])->name('items.destroy');


