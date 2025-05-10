<?php

use App\Http\Controllers\Auth\AuthController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserManagementController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\IngredientController;

// Public routes
Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);

Route::middleware(['auth:sanctum', 'ability:admin'])->group(function () {
    Route::apiResource('users', UserManagementController::class);
});

// Protected routes
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/user', [AuthController::class, 'user']);
    Route::get('/ingredient/fetch', [IngredientController::class, 'index']);
    Route::get('/product/fetch', [ProductController::class, 'index']);

    
    // Staff routes
    Route::middleware('ability:staff')->group(function () {
        // Staff endpoints here
    });
    
    // Admin routes
    Route::middleware('ability:admin')->group(function () {

        // Ingredients Routes
        Route::post('/ingredient/add', [IngredientController::class, 'store']);
        Route::put('/ingredient/update/{ingredient}', [IngredientController::class, 'update']);
        Route::patch('/ingredient/disable/{ingredient}', [IngredientController::class, 'disable']);

        // Products Routes
        Route::post('/product/add', [ProductController::class, 'store']);
        Route::put('/product/update/{product}', [ProductController::class, 'update']);
        Route::patch('/product/disable/{product}', [ProductController::class, 'disable']);
    });
});
