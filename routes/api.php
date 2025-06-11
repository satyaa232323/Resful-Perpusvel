<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\CategoryController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Public routes
Route::post('/auth/register', [AuthController::class, 'register']);
Route::post('/auth/login', [AuthController::class, 'login']);

// Protected routes
Route::middleware('auth:sanctum')->group(function () {
    // Auth routes
    Route::post('/auth/logout', [AuthController::class, 'logout']);

    // Books routes
    Route::get('/v1/book/get', [BookController::class, 'index']);
    Route::post('/v1/book/store', [BookController::class, 'store']);

    // Category routes
    Route::get('/v1/category/index', [CategoryController::class, 'index']);
    Route::post('/v1/category/store', [CategoryController::class, 'store']);
});
