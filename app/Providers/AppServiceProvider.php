<?php

namespace App\Providers;

use App\Models\Api\V1\PersonalAccessToken;
use DB;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;
use Laravel\Sanctum\Sanctum;
use Log;


class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register(): void
    {


    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot(): void
    {
        //扩展PersonalAccessToken
        Sanctum::usePersonalAccessTokenModel(PersonalAccessToken::class);

        //打开数据库日志
        if (app()->environment('local')) {
            DB::listen(function ($query) {
                Log::channel("sql")->info(Str::replaceArray('?', $query->bindings, $query->sql));
            });
        }

    }
}
