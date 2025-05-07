<?php

use App\Http\Controllers\Auth\AuthController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserManagementController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProductListController;

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

        // MainDish Routes
        Route::post('/mainDish', [ProductListController::class, 'addMainDish']);
        Route::get('/mainDishes', [ProductListController::class, 'showMainDish']);
        Route::patch('/mainDish/delete/{productList}', [ProductListController::class, 'deleteMainDish']);
        Route::put('/mainDish/update/{productList}', [ProductListController::class,'updateMainDish']);

        // Beverage List Routes
        Route::post('/beverageList/add', [ProductListController::class, 'addBeverageList']);
        Route::get('/beverageList', [ProductListController::class, 'showBeverageList']);
        Route::patch('/beverageList/delete/{productList}', [ProductListController::class, 'deleteBeverageList']);
        Route::put('/beverageList/update/{productList}', [ProductListController::class,'updateBeverageList']);

        // Dessert List Routes
        Route::post('/dessertList/add', [ProductListController::class, 'addDessertList']);
        Route::get('/dessertList', [ProductListController::class, 'showDessertList']);
        Route::patch('/dessertList/delete/{productList}', [ProductListController::class, 'deleteDessertList']);
        Route::put('/dessertList/update/{productList}', [ProductListController::class,'updateDessertList']);

        // Item List Routes
        Route::post('/itemList/add', [ProductListController::class, 'addItemList']);
        Route::get('/itemList', [ProductListController::class, 'showItemList']);
        Route::patch('/itemList/delete/{productList}', [ProductListController::class, 'deleteItemList']);
        Route::put('/itemList/update/{productList}', [ProductListController::class,'updateItemList']);
    });
});
