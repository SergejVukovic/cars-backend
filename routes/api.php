<?php

use App\Http\Controllers\AttributeController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\PostImageController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

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

Route::prefix('/auth')->group(function() {
    Route::post('/register', [AuthController::class, 'store']);
    Route::post('/login', [AuthController::class, 'login']);
});

Route::prefix('/post')->group(function() {
    Route::get('/', [PostController::class, 'index']);
    Route::get('/{post}', [PostController::class, 'show']);

    Route::prefix('/{post}/image')->group(function () {
        Route::get('/', [PostImageController::class, 'index']);
        Route::get('/{image}', [PostImageController::class, 'show']);
    });
});

Route::prefix('/category')->group(function() {
    Route::get('/', [CategoryController::class, 'index']);
    Route::get('/{category}', [CategoryController::class, 'show']);
});

Route::prefix('/attribute')->group(function() {
    Route::get('/', [AttributeController::class, 'index']);
    Route::get('/{attribute}', [AttributeController::class, 'show']);
});

Route::middleware('auth:sanctum')->group(function() {

    Route::get('/auth/logout', [AuthController::class, 'logout']);

    Route::prefix('/post')->group(function () {
        Route::post('/', [PostController::class, 'store']);
        Route::patch('/{post}', [PostController::class, 'update']);
        Route::delete('/{post}', [PostController::class, 'destroy']);

        Route::prefix('/{post}/image')->group(function () {
            Route::post('/', [PostImageController::class, 'store']);
            Route::patch('/{image}', [PostImageController::class, 'update']);
            Route::delete('/{image}', [PostImageController::class, 'destroy']);
        });
    });

    Route::prefix('/category')->group(function() {
        Route::post('/', [CategoryController::class, 'store']);
        Route::patch('/{category}', [CategoryController::class, 'update']);
        Route::delete('/{category}', [CategoryController::class, 'destroy']);
    });

    Route::prefix('/attribute')->group(function () {
        Route::post('/', [AttributeController::class, 'store']);
        Route::patch('/{attribute}', [AttributeController::class, 'update']);
        Route::delete('/{attribute}', [AttributeController::class, 'destroy']);
    });

    Route::prefix('/user')->group(function () {
        Route::get('/', [UserController::class, 'show']);
        Route::patch('/', [UserController::class, 'update']);
        Route::delete('/', [UserController::class, 'destroy']);
    });

});
