<?php

use App\Http\Controllers\Auth\AuthController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserManagementController;
use App\Http\Controllers\ProductController;

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
    
    // Staff routes
    Route::middleware('ability:staff')->group(function () {
        // Staff endpoints here
    });
    
    // Admin routes
    Route::middleware('ability:admin')->group(function () {
        
        // Ingredients Routes
        Route::post('/ingredient', [ProductController::class, 'addIngredient']);
        Route::get('/ingredients', [ProductController::class, 'showIngredients']);
        Route::patch('/ingredient/delete/{product}', [ProductController::class, 'deleteIngredient']);
        Route::put('/ingredient/update/{product}', [ProductController::class,'updateIngredient']);

        // Beverages Routes
        Route::post('/beverage', [ProductController::class, 'addBeverage']);
        Route::get('/beverages', [ProductController::class, 'showBeverages']);
        Route::patch('/beverage/delete/{product}', [ProductController::class, 'deleteBeverage']);
        Route::put('/beverage/update/{product}', [ProductController::class,'updateBeverage']);

        // Desserts Routes
        Route::post('/dessert', [ProductController::class, 'addDessert']);
        Route::get('/desserts', [ProductController::class, 'showDesserts']);
        Route::patch('/dessert/delete/{product}', [ProductController::class, 'deleteDessert']);
        Route::put('/dessert/update/{product}', [ProductController::class,'updateDessert']);

        // Others Routes
        Route::post('/other', [ProductController::class, 'addOther']);
        Route::get('/others', [ProductController::class, 'showOthers']);
        Route::patch('/other/delete/{product}', [ProductController::class, 'deleteOther']);
        Route::put('/other/update/{product}', [ProductController::class,'updateOther']);
    });
});
