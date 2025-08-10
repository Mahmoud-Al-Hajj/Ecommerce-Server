<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ChatController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Common\AuthController;
use App\Http\Controllers\Common\ProductsController;
use App\Http\Controllers\User\NotificationsController;
use App\Http\Controllers\User\OrdersController;
use App\Http\Controllers\WebhookLogController;

Route::group(["middleware" => "auth:api"], function () {
    Route::group(["middleware" => "isAdmin"], function () {
        Route::post("/createProduct", [ProductsController::class, "createProduct"]);
        Route::post("/updateProduct/{id}", [ProductsController::class, "updateProduct"]);
        Route::delete("/deleteProduct/{id}", [ProductsController::class, "deleteProduct"]);
        Route::get('/orders', [OrdersController::class, 'allOrders']);
        Route::get('/order/{id}', [OrdersController::class, 'getOrderById']);
        Route::post('/order/{id}/status', [OrdersController::class, 'updateOrderStatus']);
        Route::get('/orders/revenue/today', [OrdersController::class, 'getTodaysRevenue']);
        Route::get('/usersCount', [AuthController::class, 'getUsersCount']);
    });
    Route::get('/myOrders', [OrdersController::class, 'myOrders']);
    Route::post('/createOrder', [OrdersController::class, 'createOrder']);
    //all of the above routes are for user only
    Route::delete('/order/{id}', [OrdersController::class, 'deleteOrder']);
    //common routes for both admin and user

    Route::get('/notifications', [NotificationsController::class, 'getAllNotifications']);
    Route::post('/notifications/{id}/read', [NotificationsController::class, 'markAsRead']);
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::post('/chat', [ChatController::class, 'ask']);

});


Route::get('categories', [CategoryController::class, 'GetAllCategories']);
Route::get("/product/{id}", [ProductsController::class, "getProductById"]);
Route::get("/products", [ProductsController::class, "getAllProducts"]);
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
