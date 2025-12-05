<?php

use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
use Illuminate\Http\Request;
use App\Http\Controllers\Auth\AuthController;
use Illuminate\Support\Facades\Route;

Route::prefix('auth')->group(function(){
    Route::post('register', [AuthController::class,'register']);
    Route::post('login', [AuthController::class,'login']);
    Route::middleware('auth:api')->post('logout', [AuthController::class,'logout']);
    Route::middleware('auth:api')->get('me', [AuthController::class,'me']);
    Route::middleware('auth:api')->post('token', [AuthController::class,'refresh']);

});

Route::group(['middleware' => 'auth:api'], function(){
   Route::apiResource('products', ProductController::class);
    Route::post('orders', [OrderController::class,'store']);
    Route::get('orders', [OrderController::class,'index']);
    Route::get('orders/{id}', [OrderController::class,'show']);
});

