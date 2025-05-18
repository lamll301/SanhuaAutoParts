<?php

use App\Http\Controllers\CartController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:api')->prefix('carts')->group(function () {
    Route::post('/{id}', [CartController::class, 'add']);
    Route::patch('/{id}', [CartController::class, 'update']);
    Route::delete('/clear', [CartController::class, 'clear']);
    Route::delete('/{id}', [CartController::class, 'remove']);
    Route::get('/', [CartController::class, 'index']);
});