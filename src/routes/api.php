<?php

use App\Admin\Controllers\Api\ProductController;
use App\Http\Controllers\Auth\RegisteredUserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CropperController;
use App\Http\Controllers\Shop\OrderController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->group( function () {
    Route::post('users', [RegisteredUserController::class, 'sync']);
    Route::post('orders', [OrderController::class, 'sync']);
});

Route::prefix('product')->group(function () {
    Route::get('product', [ProductController::class, 'getById']);
    Route::get('name', [ProductController::class, 'getProductNameById']);
    Route::get('sizes', [ProductController::class, 'sizesByProductId']);
});

// Route::post('croppic/save', [CropperController::class, 'save']);
Route::post('croppic/crop', [CropperController::class, 'crop']);
