<?php

namespace App\Providers;

use Illuminate\Support\Facades\Broadcast;
use Illuminate\Support\ServiceProvider;

class BroadcastServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //  Broadcast::routes();
        //routes()参数如果不配置 默认走的是web中间件
        //'middleware' => 'auth:sanctum' 然后广播路由走auth:sanctum中间件
        Broadcast::routes(['middleware' => 'auth:sanctum', "prefix" => "api/v1"]);

        require base_path('routes/channels.php');
    }
}
