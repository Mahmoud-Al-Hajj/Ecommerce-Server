<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Common\AuthController;
use App\Http\User\Controllers\OrdersController;
use App\Http\Common\Controllers\ProductsController;
use App\Http\Admin\Controllers\WebhookLogsController;


Route::get("/products", [ProductsController::class, "getAllProducts"]);
Route::get("/product/{id}", [ProductsController::class, "getProductById"]);
Route::post("/createProduct", [ProductsController::class, "createProduct"]);
Route::post("/updateProduct/{id}", [ProductsController::class, "updateProduct"]);
Route::delete("/deleteProduct/{id}", [ProductsController::class, "deleteProduct"]);
Route::get("/orders", [OrdersController::class, "myOrders"]);

Route::post("/webhook", [WebhookLogsController::class, "WebhookLogs"]);




Route::post("/register", [AuthController::class, "register"]);
Route::post("/login", [AuthController::class, "login"]);
Route::post("/logout", [AuthController::class, "logout"]);


