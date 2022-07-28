<?php


use App\Http\Controllers\BroadCastsController;
use App\Http\Controllers\IndexController;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', [IndexController::class, "index"]);
Route::get('/users', [IndexController::class, "users"]);
Route::get('/search', [IndexController::class, "search"]);

Route::get("/event", [BroadCastsController::class, "event"]);
Route::get("/private/{id}", [BroadCastsController::class, "priEvent"]);
Route::get("/chat/{rid}", [BroadCastsController::class, "chat"]);
Route::get("/notification/{id}", [BroadCastsController::class, "notification"]);

