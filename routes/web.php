<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\PaymentMethodController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProductImportController;
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

Route::get('/dashboard/chart-data', [DashboardController::class, 'chartData'])
    ->middleware('auth')
    ->name('dashboard.chart-data');

Route::resource('products', ProductController::class)
    ->middleware('auth');

Route::prefix('products-import')->middleware('auth')->group(function () {
    Route::get('/', [ProductImportController::class, 'show'])->name('products.import');
    Route::post('/preview', [ProductImportController::class, 'preview'])->name('products.import.preview');
    Route::post('/store', [ProductImportController::class, 'store'])->name('products.import.store');
    Route::get('/template', [ProductImportController::class, 'downloadTemplate'])->name('products.import.template');
});

Route::resource('orders', OrderController::class)
    ->only(['index', 'create', 'store', 'show'])
    ->middleware('auth');

Route::get('orders/details/{document_number}', [OrderController::class, 'getDetails'])
    ->name('orders.details')
    ->middleware('auth');

Route::prefix('treasury')->middleware('auth')->group(function () {
    Route::resource('payment-methods', PaymentMethodController::class)
        ->except(['show']);
    Route::resource('payments', PaymentController::class)
        ->only(['index', 'create', 'store']);
});
