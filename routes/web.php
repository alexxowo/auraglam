<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('dashboard');
});

Route::prefix('auth')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware('auth')
    ->name('dashboard');

Route::resource('products', App\Http\Controllers\ProductController::class)
    ->middleware('auth');

Route::resource('orders', App\Http\Controllers\OrderController::class)
    ->only(['index', 'create', 'store', 'show'])
    ->middleware('auth');

Route::get('orders/details/{document_number}', [App\Http\Controllers\OrderController::class, 'getDetails'])
    ->name('orders.details')
    ->middleware('auth');

Route::prefix('treasury')->middleware('auth')->group(function () {
    Route::resource('payment-methods', App\Http\Controllers\PaymentMethodController::class)
        ->except(['show']);
    Route::resource('payments', App\Http\Controllers\PaymentController::class)
        ->only(['index', 'create', 'store']);
});
