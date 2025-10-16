<?php

use App\Http\Controllers\Api\v1\AuthController;
use App\Http\Controllers\Api\v1\BoardController;
use App\Http\Controllers\Api\v1\NotificationController;
use App\Http\Controllers\Api\v1\ProjectController;
use App\Http\Controllers\Api\v1\TaskController;
use App\Http\Controllers\Api\v1\UserController;
use Illuminate\Support\Facades\Route;

Route::prefix('auth')->group(function () {

    Route::post('login', [AuthController::class, 'login']);
    Route::post('logout', [AuthController::class, 'logout']);
});

Route::prefix('users')->group(function () {

    Route::post('', [UserController::class, 'create']);

    Route::middleware('auth:sanctum')->group(function () {
        Route::get('/{id}', [UserController::class, 'getUserById']);
        Route::put('/{id}', [UserController::class, 'update']);
    });
});


Route::middleware('auth:sanctum')->group(function () {

    Route::prefix('projects')->group(function () {

        Route::get('', [ProjectController::class, 'getAllByUser']);

        Route::middleware('admin')->group(function () {
            Route::post('', [ProjectController::class, 'create']);
            Route::put('{id}', [ProjectController::class, 'update']);
        });
    });

    Route::prefix('boards')->group(function () {
        Route::get('{id}', [BoardController::class, 'getAllByProject']);
        Route::post('', [BoardController::class, 'create']);
        Route::put('{id}', [BoardController::class, 'update']);
    });

    Route::prefix('tasks')->group(function () {
        Route::get('board/{boardId}', [TaskController::class, 'getAllByBoard']);
        Route::post('', [TaskController::class, 'create']);
        Route::put('{id}', [TaskController::class, 'update']);
    });


    Route::prefix('notifications')->group(function () {

        Route::get('', [NotificationController::class, 'getByUser']);
        Route::post('', [NotificationController::class, 'create']);
        Route::post('{id}/read', [NotificationController::class, 'markAsRead']);

    });
});
