<?php


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
    return "event:" . time();
});


Route::get("/pevent", function () {
    PrivateMessageNotification::dispatch(1, "用户1你有一条新的工作待完成" . time(), '提示信息', 'success');
    return "event:" . time();
});


Route::get("/code", function () {

    dispatch(new AliSmsQueue('13516872342', AliSms::codeMessage(1234)))->onQueue("sms");
    return "send sms:" . time();
});
