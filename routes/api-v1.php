<?php

use App\Events\ChatRoomEvent;
use App\Http\Controllers\Api\V1\AuthenticateController;
use App\Http\Controllers\Api\V1\BroadcastController;
use App\Http\Controllers\Api\V1\CodeController;
use App\Http\Controllers\Api\V1\SystemController;
use App\Http\Controllers\Api\V1\WechatController;
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
    return "api v1 works";
});

Route::get("/checkTicket", [AuthenticateController::class, "checkTicket"])->name("authenticate.checkTicket");

//1分钟，频次上限120
Route::group(['middleware' => 'throttle:120,1'], function () {
    //验证码
    Route::post("/code", [CodeController::class, "send"])->name("code.send");
    Route::get("/getQrcode", [AuthenticateController::class, "getQrcode"])->name("authenticate.getQrcode");
    Route::get("/qrcode", [AuthenticateController::class, "qrcode"])->name("authenticate.qrcode");
    Route::get("/wechat", [AuthenticateController::class, "wechat"])->name("authenticate.wechat");


    Route::get('/serve', [WechatController::class, 'serve'])->name("wechat.serve");
    Route::get('/auth_code', [WechatController::class, 'oauth_code'])->name("wechat.oauth_code");
    Route::get('/wechat/oauth_back', [WechatController::class, 'oauth_back'])->name("wechat.oauth_back");
});


//获取加密公钥
Route::get('/publicKey', [AuthenticateController::class, "publicKey"])->name("authenticate.publicKey");
//用户登陆
Route::post('/login', [AuthenticateController::class, "login"])->name("authenticate.login");


Route::group(['middleware' => 'auth:sanctum'], function () {
    //获取用户
    Route::post('/user/current', [AuthenticateController::class, "current"])->name("authenticate.current");
    //退出登陆
    Route::post('/user/logout', [AuthenticateController::class, "logout"])->name("authenticate.logout");
    //清除缓存
    Route::get('/clearCache', [SystemController::class, "clearCache"])->name("system.clearCache");

    //聊天室广播
    Route::get("/chat", function () {
        $user = request()->user();
        $roomId = 1;
        broadcast(new ChatRoomEvent($roomId, "来自" . $user->id . ":你好!" . time()))->toOthers();
        return "聊天室广播:" . time();
    });

});
