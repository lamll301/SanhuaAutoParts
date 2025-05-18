<?php

use App\Http\Controllers\OrderController;
use App\Http\Controllers\PaymentController;
use App\Http\Middleware\SortMiddleware;
use App\Http\Middleware\AuthenticateWithJWT;
use Illuminate\Support\Facades\Route;

Route::prefix('orders')->group(function () {
    Route::patch('/{id}/status', [OrderController::class, 'updateStatus'])->middleware(AuthenticateWithJWT::class);
    Route::get('/trashed', [OrderController::class, 'trashed'])->middleware(SortMiddleware::class);
    Route::get('/view', [OrderController::class, 'viewList'])->middleware(AuthenticateWithJWT::class);
    Route::get('/{id}/view', [OrderController::class, 'view'])->middleware(AuthenticateWithJWT::class);
    Route::get('/{id}', [OrderController::class, 'show']);
    Route::post('/handle-form-actions', [OrderController::class, 'handleFormActions']);
    Route::post('/', [OrderController::class, 'store'])->middleware(AuthenticateWithJWT::class);
    Route::patch('/{id}/restore', [OrderController::class, 'restore']);
    Route::delete('/{id}/force-delete', [OrderController::class, 'forceDelete']);
    Route::delete('/{id}', [OrderController::class, 'destroy']);
    Route::patch('/{id}/cancel', [OrderController::class, 'cancel']);
    Route::get('/', [OrderController::class, 'index'])->middleware(SortMiddleware::class);
});

Route::prefix('payments')->group(function () {
    Route::post('/momo', [PaymentController::class, 'createMomoPayment'])->middleware(AuthenticateWithJWT::class);
    Route::post('/momo/ipn', [PaymentController::class, 'momoIPN']);
    Route::post('/vnpay', [PaymentController::class, 'createVNPayPayment'])->middleware(AuthenticateWithJWT::class);
    Route::post('/vnpay/ipn', [PaymentController::class, 'vnpayIPN']);
    Route::get('/vnpay/return', [PaymentController::class, 'vnpayReturn']);
    Route::post('/zalopay', [PaymentController::class, 'createZaloPayPayment'])->middleware(AuthenticateWithJWT::class);
    Route::post('/zalopay/callback', [PaymentController::class, 'zaloPayCallback']);
    Route::post('/cod', [PaymentController::class, 'CODPayment'])->middleware(AuthenticateWithJWT::class);
});
