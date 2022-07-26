<?php

namespace App\Providers;

use App\Models\Api\V1\PersonalAccessToken;
use Illuminate\Support\ServiceProvider;
use Laravel\Sanctum\Sanctum;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register(): void
    {
        //
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

    }
}
