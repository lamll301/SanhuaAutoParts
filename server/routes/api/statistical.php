<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\StatisticalController;
use App\Http\Middleware\AuthenticateWithJWT;
use App\Http\Middleware\Authorization;
use App\Http\Middleware\SortMiddleware;

Route::prefix('statistical')->group(function () {
    Route::get('/revenue', [StatisticalController::class, 'revenueByPeriod'])
        ->middleware([ 
            AuthenticateWithJWT::class, 
            Authorization::class . ':view'
        ]);
    Route::get('/profit', [StatisticalController::class, 'profitByPeriod'])
        ->middleware([AuthenticateWithJWT::class, Authorization::class . ':view']);
    Route::get('/completed-orders', [StatisticalController::class, 'getCompletedOrdersByPeriod'])
        ->middleware([SortMiddleware::class, AuthenticateWithJWT::class, Authorization::class . ':view']);
    Route::get('/order', [StatisticalController::class, 'orderStatistics'])
        ->middleware([
            AuthenticateWithJWT::class,
            Authorization::class . ':view'
        ]);
    Route::get('/product/best-selling', [StatisticalController::class, 'bestSellingProducts'])
        ->middleware([AuthenticateWithJWT::class, Authorization::class . ':view']);
    Route::get('/product/expired', [StatisticalController::class, 'productExpiredStatistics'])
        ->middleware([AuthenticateWithJWT::class, Authorization::class . ':view']);
    Route::get('/customer', [StatisticalController::class, 'customerStatistics'])
        ->middleware([AuthenticateWithJWT::class, Authorization::class . ':view']);
    Route::get('/summary', [StatisticalController::class, 'summary'])
        ->middleware([AuthenticateWithJWT::class, Authorization::class . ':view']);
});