<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CartController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ManagementController;

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');

Route::post('password/forget', [UserController::class, 'forgetPassword']);
Route::post('resetpassword/{token}', [UserController::class, 'resetPassword']);

Route::group(['middleware'=>'api'], function(){
    
    Route::post('register', [UserController::class, 'register']);
    Route::post('login', [UserController::class, 'login']);
    Route::post('refresh', [UserController::class, 'refreshToken']);

    Route::middleware('authenticate:api')->group(function(){

        Route::get('profile', [UserController::class, 'profile']);
        Route::post('editprofile', [UserController::class, 'editprofile']);
        Route::post('logout', [UserController::class, 'logout']);

        Route::post('cart/add', [CartController::class, 'addToCart']);
        Route::put('cart/updateCart', [CartController::class, 'update']);
        Route::post('cart/remove', [CartController::class, 'removeFromCart']);
        Route::get('cart', [CartController::class, 'viewCart']);
        Route::get('/cart/item-count', [CartController::class, 'getItemCount']);
    
    });

});

Route::apiResource('product',ProductController::class);
Route::get('admin/products',[ProductController::class, 'adminindex']);
Route::get('search', [ProductController::class, 'search']);

Route::get('categories', [CategoryController::class, 'index']);
Route::get('categories/{category}', [CategoryController::class, 'show']);
Route::get('getCategories', [CategoryController::class, 'getCategories']);

Route::get('users',[ManagementController::class, 'index']);
Route::put('users/{id}',[ManagementController::class, 'update']);