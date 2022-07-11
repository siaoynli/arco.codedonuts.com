<?php

use App\Http\Controllers\Api\V1\AuthenticateController;
use App\Http\Controllers\Api\V1\CodeController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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


Route::get('/', function (Request $request) {
    return "arco api v1";
});

//1分钟，频次上限10
Route::group(['middleware' => 'throttle:10,1'], function () {
    //验证码
    Route::post("/code", [CodeController::class, "send"])->name("code.send");
});


//创建令牌
Route::get('/login', [AuthenticateController::class, "login"])->name("user.login");


Route::group(['middleware' => 'auth:sanctum'], function () {
    //获取用户
    Route::get('/user', [AuthenticateController::class, "current"])->name("user.current");
    //退出登陆
    Route::get('/logout', [AuthenticateController::class, "logout"])->name("user.logout");
});
