<?php

use App\Http\Controllers\ShoppingItemController;
use Illuminate\Support\Facades\Route;

Route::get('/', [ShoppingItemController::class, 'index'])->name('board');

Route::post('/items', [ShoppingItemController::class, 'store'])->name('items.store');
Route::put('/items/{shoppingItem}', [ShoppingItemController::class, 'update'])->name('items.update');
Route::patch('/items/{shoppingItem}/status', [ShoppingItemController::class, 'updateStatus'])->name('items.update-status');
Route::delete('/items/{shoppingItem}', [ShoppingItemController::class, 'destroy'])->name('items.destroy');
