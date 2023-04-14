<?php

use App\Http\Controllers\Api\{
    AuthController,
    ClientController,
    ProducerController,
    StateCitiesController,
    UserController
};

Route::group(['middleware' => 'jwt.middleware'], function ($router) {

    /**
     * Rotas de Autenticação
     */
    Route::group(['prefix' => 'auth'], function ($router) {
        Route::post('login', [AuthController::class, 'login']);
        Route::post('create-password-reset-token', [AuthController::class, 'createAPasswordResetToken']);
        Route::post('check-password-reset-token', [AuthController::class, 'checkPasswordResetToken']);
        Route::post('reset-password', [AuthController::class, 'passwordReset']);
        Route::post('logout', [AuthController::class, 'logout']);
        Route::post('refresh', [AuthController::class, 'refresh']);
        Route::get('me', [AuthController::class, 'me']);
    });

    Route::group(['middleware' => 'check.user.admin'], function ($router) {

        Route::get('state-cities/{uf?}', [StateCitiesController::class, 'index']);

        /**
         * Rotas de Usuários
         */
        Route::apiResource('users', UserController::class);

        /**
         * Rotas de Clientes
         */
        Route::apiResource('clients', ClientController::class);

        /**
         * Rotas de Produtores exceto o método index e show
         */
        Route::apiResource('producers', ProducerController::class)->except(['index', 'show']);
    });

    /**
     * Rotas de listagem de Produtores
     */
    Route::get('producers', [ProducerController::class, 'index']);
    Route::get('producers/{id}', [ProducerController::class, 'show']);
});
