<?php


use App\Http\Controllers\Api\V1\AuthController;
use App\Http\Controllers\Api\V1\BroadCastsController;
use App\Http\Controllers\Api\V1\CodesController;
use App\Http\Controllers\Api\V1\IndexController;
use App\Http\Controllers\Api\V1\SystemController;
use App\Http\Controllers\Api\V1\WechatController;


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



Route::get('/', [IndexController::class, "index"]);

Route::get("/checkTicket", [AuthController::class, "checkTicket"])->name("auth.checkTicket");

//1分钟，频次上限120
Route::group(['middleware' => 'throttle:120,1'], function () {
    //验证码
    Route::post("/code", [CodesController::class, "send"])->name("code.send");
    Route::get("/getQrcode", [AuthController::class, "getQrcode"])->name("auth.getQrcode");
    Route::get("/qrcode", [AuthController::class, "qrcode"])->name("auth.qrcode");
    Route::get("/wechat", [AuthController::class, "wechat"])->name("auth.wechat");


    Route::get('/serve', [WechatController::class, 'serve'])->name("wechat.serve");
    Route::get('/auth_code', [WechatController::class, 'oauth_code'])->name("wechat.oauth_code");
    Route::get('/wechat/oauth_back', [WechatController::class, 'oauth_back'])->name("wechat.oauth_back");
});


//获取加密公钥
Route::get('/publicKey', [AuthController::class, "publicKey"])->name("auth.publicKey");
//用户登陆
Route::post('/login', [AuthController::class, "login"])->name("auth.login");


Route::group(['middleware' => 'auth:sanctum'], function () {
    //获取用户
    Route::post('/user/current', [AuthController::class, "current"])->name("auth.current");
    //退出登陆
    Route::post('/user/logout', [AuthController::class, "logout"])->name("auth.logout");
    //清除缓存
    Route::get('/clearCache', [SystemController::class, "clearCache"])->name("system.clearCache");

    //聊天室广播
    Route::get("/chat", [BroadCastsController::class, "chat"]);

});
