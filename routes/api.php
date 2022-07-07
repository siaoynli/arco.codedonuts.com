<?php

use App\Models\User;
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
Route::get('/sanctum/token', function (Request $request) {
    $user = User::where('id', 1)->first();
    $device_name = $request->get("device_name", "webkit");
    return $user->createToken($device_name)->plainTextToken;
});



Route::group(['middleware' => 'auth:sanctum'], function () {
    //获取用户
    Route::get('/user', function (Request $request) {
        return $request->user();
    });
    //退出登陆
    Route::get('/logout', function (Request $request) {
        //撤销所有令牌
        return $request->user()->tokens()->delete();
    });
});
