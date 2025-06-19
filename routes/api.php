<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\BorrowController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CommunityController;
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
    Route::get('/v1/admin/book/get', [BookController::class, 'index']);
    Route::post('/v1/admin/book/store', [BookController::class, 'store']);
    Route::put('/v1/admin/book/update/{slug}', [BookController::class, 'update']);
    Route::delete('/v1/admin/book/delete/{slug}', [BookController::class, 'destroy']);

    // Category routes
    Route::get('/v1/admin/category/index', [CategoryController::class, 'index']);
    Route::post('/v1/admin/category/store', [CategoryController::class, 'store']);
    Route::put('/v1/admin/category/update/{id}', [CategoryController::class, 'update']);
    Route::delete('/v1/admin/category/delete/{id}', [CategoryController::class, 'destroy']);


    // Borrowing routes
   
    Route::post('/v1/book/borrowing', [BorrowController::class, 'Borrowing']);
    Route::get('/v1/book/my-borrowing-list', [BorrowController::class, 'index']);
    Route::post('/v1/book/returning', [BorrowController::class, 'returnning']);

    // Community routes
    Route::prefix('v1/communities')->group(function () {
        Route::get('/', [CommunityController::class, 'index']);
        Route::post('/', [CommunityController::class, 'store']);
        Route::get('/{id}', [CommunityController::class, 'show']);
        Route::put('/{id}', [CommunityController::class, 'update']);
        Route::delete('/{id}', [CommunityController::class, 'destroy']);
    });
});