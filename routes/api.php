<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProductController;

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');

Route::post('password/forget', [UserController::class, 'forgetPassword']);
Route::post('resetpassword/{token}', [UserController::class, 'resetPassword']);

Route::group(['middleware'=>'api'], function($routes){
    
    Route::post('register', [UserController::class, 'register']);

    Route::post('login', [UserController::class, 'login']);
    
    Route::post('refresh', [UserController::class, 'refreshToken']);

    Route::middleware('authenticate:api')->group(function(){

        Route::get('profile', [UserController::class, 'profile']);

        Route::post('editprofile', [UserController::class, 'editprofile']);
    
        Route::post('logout', [UserController::class, 'logout']);
    
    });

});

Route::apiResource('product',ProductController::class);
Route::get('search', [ProductController::class, 'search']);