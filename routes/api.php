<?php

use GuzzleHttp\Middleware;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');

Route::group(['middleware'=>'api'], function($routes){
    
    Route::post('register', [UserController::class, 'register']);

    Route::post('login', [UserController::class, 'login']);

    Route::middleware('authenticate:api')->group(function(){

        Route::post('refresh', [UserController::class, 'refreshToken']);

        Route::get('profile', [UserController::class, 'profile']);
    
        Route::post('logout', [UserController::class, 'logout']);
    
    });

});
