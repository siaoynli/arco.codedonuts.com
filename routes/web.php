<?php


use App\Events\ChatRoomEvent;
use App\Events\MessageNotification;
use App\Events\PrivateMessageNotification;
use App\Jobs\AliSmsQueue;
use App\Utils\AliSms;
use Illuminate\Support\Facades\Route;

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

Route::get('/', function () {
    return ping();
});


Route::get("/event", function () {
    MessageNotification::dispatch("你有一条新的工作待完成" . time(), '提示信息', 'success');
    return "公共广播:" . time();
});


Route::get("/pevent/{id}", function ($id) {
    PrivateMessageNotification::dispatch($id, "用户" . $id . "你有一条新的工作待完成!" . time(), '提示信息', 'success');
    return "私有广播:" . time();
});

Route::get("/chat/{uid}", function ($uid) {
    $roomId = 1;
    ChatRoomEvent::dispatch($roomId, "来自" . $uid . ":你好!" . time());
    return "聊天室广播:" . time();
});


Route::get("/code", function () {

    dispatch(new AliSmsQueue('13516872342', AliSms::codeMessage(1234)))->onQueue("sms");
    return "send sms:" . time();
});
