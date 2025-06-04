<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ChatController;
use App\Http\Middleware\AuthenticateWithJWT;
use App\Http\Middleware\Authorization;
use App\Http\Middleware\SortMiddleware;

Route::prefix('chat')->group(function () {
    Route::get('/conversation', [ChatController::class, 'getConversationByCustomer'])->middleware([
        AuthenticateWithJWT::class
    ]);
    Route::get('/{conversation}/messages', [ChatController::class, 'getMessages'])->middleware([
        AuthenticateWithJWT::class
    ]);
    Route::post('/{conversation}/messages', [ChatController::class, 'sendMessage'])->middleware([
        AuthenticateWithJWT::class
    ]);
    Route::patch('/{conversation}/mark-read', [ChatController::class, 'markAsRead'])->middleware([
        AuthenticateWithJWT::class
    ]);
    Route::patch('/{conversation}/connect', [ChatController::class, 'connect'])->middleware([
        AuthenticateWithJWT::class, Authorization::class . ':chat'
    ]);
    Route::get('/{id}', [ChatController::class, 'show'])->middleware([
        AuthenticateWithJWT::class, Authorization::class . ':chat'
    ]);
    Route::get('/', [ChatController::class, 'index'])->middleware([
        SortMiddleware::class, AuthenticateWithJWT::class, Authorization::class . ':chat'
    ]);
});