<?php

namespace App\Providers;


use Elastic\Elasticsearch\ClientBuilder;
use Elastic\Transport\Exception\NoNodeAvailableException;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\ServiceProvider;
use Symfony\Component\HttpClient\Psr18Client;

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
                ->setHttpClient(new Psr18Client)
                ->setHosts(config('database.elasticsearch.hosts'))
                ->setBasicAuthentication('elastic', config('database.elasticsearch.password'))
                ->setCABundle(resource_path("certs/es_ca.crt"));

            // 如果是开发环境
            if (app()->environment() === 'local') {
                // 配置日志，Elasticsearch 的请求和返回数据将打印到日志 es channel 文件中，方便我们调试
                $builder->setLogger(app('log')->driver('elasticsearch'));
            }
            $client = $builder->build();

            try {
                $client->info();
            } catch (NoNodeAvailableException $e) {
                Log::channel('elasticsearch')->info($e->getMessage());
            }
            return $client;

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
