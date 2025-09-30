<?php

use App\Http\Controllers\Api\v1\AuthController;
use App\Http\Controllers\Api\v1\BoardController;
use App\Http\Controllers\Api\v1\ProjectController;
use App\Http\Controllers\Api\v1\UserController;
use Illuminate\Support\Facades\Route;

Route::prefix('auth')->group(function () {

    Route::post('login', [AuthController::class, 'login']);
    Route::post('logout', [AuthController::class, 'logout']);

});

Route::prefix('user')->group(function () {

    Route::post('/store', [UserController::class, 'create']);

    Route::middleware('auth:sanctum')->group(function () {

        Route::get('/{id}', [UserController::class, 'getUserById']);
        Route::put('/update/{id}', [UserController::class, 'update']);

    });

});

Route::middleware('auth:sanctum')->group(function () {

    Route::prefix('project')->group(function () {

        Route::get('', [ProjectController::class, 'getAllByUser']);

        Route::middleware('admin')->group(function () {
            Route::post('create', [ProjectController::class, 'create']);
            Route::put('update/{id}', [ProjectController::class, 'update']);
        });

    });


    Route::prefix('board')->group(function () {

        Route::get('{id}', [BoardController::class, 'getAllByProject']);
        Route::post('create', [BoardController::class, 'create']);
        Route::put('update/{id}', [BoardController::class, 'update']);
        
    });

});