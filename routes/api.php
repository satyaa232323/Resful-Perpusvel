<?php

use App\Http\Controllers\BookController;
use App\Http\Controllers\CategoryController;
use Illuminate\Container\Attributes\Authenticated;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');


Route::get('/v1/book/get', [BookController::class, 'index']);
Route::post('/v1/book/store', [BookController::class, 'store']);


Route::get('/v1/category/index', action: [CategoryController::class, 'index']);
Route::post('/v1/category/store', [CategoryController::class, 'store']);



// Route::apiResource('/v1/category/store', BookController::class);