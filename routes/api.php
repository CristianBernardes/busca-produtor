<?php

use App\Http\Controllers\ClientController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;


Route::post('clients', [ClientController::class, 'create']);
Route::post('users', [UserController::class, 'create']);
Route::get('user/{id}', [UserController::class, 'show']);
