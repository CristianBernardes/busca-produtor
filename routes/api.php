<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\ProducerController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;


Route::group(['middleware' => 'api', 'prefix' => 'auth'], function ($router) {
    Route::post('login', [AuthController::class, 'login']);
    Route::post('logout', 'AuthController@logout');
    Route::post('refresh', 'AuthController@refresh');
    Route::get('me', [AuthController::class, 'me']);
});

Route::group(['middleware' => 'auth:api'], function ($router) {
    Route::apiResource('producers', ProducerController::class);
    Route::apiResource('clients', ClientController::class);
    Route::apiResource('users', UserController::class);
});
