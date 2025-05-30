<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\VoucherController;
use App\Http\Middleware\SortMiddleware;
use App\Http\Middleware\AuthenticateWithJWT;
use App\Http\Middleware\Authorization;

Route::prefix('vouchers')->group(function () {
    Route::get('/check/{couponCode}', [VoucherController::class, 'checkCoupon'])->middleware(AuthenticateWithJWT::class);
    Route::patch('/{id}/approve', [VoucherController::class, 'approve'])->middleware([
        AuthenticateWithJWT::class, Authorization::class . ':vouchers.approve'
    ]);
    Route::get('/{id}/usage', [VoucherController::class, 'getVoucherUsage'])->middleware([
        AuthenticateWithJWT::class, Authorization::class . ':view'
    ]);
    Route::get('/trashed', [VoucherController::class, 'trashed'])->middleware([
        SortMiddleware::class, AuthenticateWithJWT::class, Authorization::class . ':view'
    ]);
    Route::patch('/{id}/restore', [VoucherController::class, 'restore'])->middleware([
        AuthenticateWithJWT::class, Authorization::class . ':vouchers.manage'
    ]);
    Route::delete('/{id}/force-delete', [VoucherController::class, 'forceDelete'])->middleware([
        AuthenticateWithJWT::class, Authorization::class . ':vouchers.manage'
    ]);
    Route::post('/handle-form-actions', [VoucherController::class, 'handleFormActions'])->middleware([
        AuthenticateWithJWT::class, Authorization::class . ':vouchers.manage'
    ]);
    Route::post('/', [VoucherController::class, 'store'])->middleware([
        AuthenticateWithJWT::class, Authorization::class . ':vouchers.manage'
    ]);
    Route::put('/{id}', [VoucherController::class, 'update'])->middleware([
        AuthenticateWithJWT::class, Authorization::class . ':vouchers.manage'
    ]);
    Route::delete('/{id}', [VoucherController::class, 'destroy'])->middleware([
        AuthenticateWithJWT::class, Authorization::class . ':vouchers.manage'
    ]);
    Route::get('/{id}', [VoucherController::class, 'show'])->middleware([
        AuthenticateWithJWT::class, Authorization::class . ':view'
    ]);
    Route::get('/', [VoucherController::class, 'index'])->middleware([
        SortMiddleware::class, AuthenticateWithJWT::class, Authorization::class . ':view'
    ]);
});