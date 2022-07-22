<?php


use App\Events\PusherEvent;
use App\Events\PusherPrivateEvent;
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
//    event(new PusherEvent('hello world'));
    PusherEvent::dispatch('hello world');
    return ping();
});
