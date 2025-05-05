<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\OrderController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Dashboard
Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

// Users
Route::resource('users', UserController::class);

// Products
Route::resource('products', ProductController::class);

// Orders
Route::resource('orders', OrderController::class);
Route::get('users/{user}/orders', [OrderController::class, 'userOrders'])->name('users.orders');
Route::get('products/{product}/orders', [OrderController::class, 'productOrders'])->name('products.orders');