<?php

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

    dispatch(new \App\Jobs\AliSmsQueue('13516872342', \App\Utils\AliSms::CodeMessage('6748')))->onQueue("sms");
    return ping();
});
