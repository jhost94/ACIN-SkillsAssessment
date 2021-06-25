<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\OrderController;
use App\Models\Order;

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

/* Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
}); */

Route::post("/register", [UserController::class, "register"])->name("register");

Route::post("/login", [UserController::class, "login"])->name("login");

Route::post("/order/place", [OrderController::class, "placeOrder"])->middleware("auth:api");

Route::get("/order", [OrderController::class, "getOrders"])->middleware("auth:api");
