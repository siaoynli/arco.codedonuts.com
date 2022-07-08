<?php

use App\Http\Controllers\Api\V1\AuthenticateController;
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


//创建令牌
Route::get('/login', [AuthenticateController::class, "login"]);


Route::group(['middleware' => 'auth:sanctum'], function () {
    //获取用户
    Route::get('/user', [AuthenticateController::class, "current"]);
    //退出登陆
    Route::get('/logout', [AuthenticateController::class, "logout"]);
});
