<?php

use App\Http\Controllers\OrderController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::apiResource('orders', OrderController::class);
Route::get('/orders/user/{user_id}', [OrderController::class, 'getOrdersByUser']);
Route::get('/orders/product/{product_id}', [OrderController::class, 'getOrdersByProduct']);
