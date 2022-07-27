<?php

namespace App\Providers;

use Elasticsearch\ClientBuilder;
use Illuminate\Support\ServiceProvider;

class ElasticSearchProvider extends ServiceProvider
{
    /**
     * @Author: lixiaoyun
     * @Email: 120235331@qq.com
     * @Date: 2022/7/27 14:41
     * @Description: 注册es
     * @return void
     */
    public function register()
    {
        // 注册一个名为 es 的单例
        $this->app->singleton('es', function () {
            // 从配置文件读取 Elasticsearch 服务器列表
            $builder = ClientBuilder::create()
                ->setHosts(config('database.elasticsearch.hosts'));
            //  ->setBasicAuthentication('elastic', 'password copied during Elasticsearch start');
            // 如果是开发环境
            if (app()->environment() === 'local') {
                // 配置日志，Elasticsearch 的请求和返回数据将打印到日志 es channel 文件中，方便我们调试
                $builder->setLogger(app('log')->driver('elasticsearch'));
            }
            return $builder->build();
        });
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {

    }
}
