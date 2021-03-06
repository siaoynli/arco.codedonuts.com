<?php

/*
 * @Author     : lixiaoyun
 * @Email      : 120235331@qq.com
 * @Github     : http://www.github.com/siaoynli
 * @Date       : 2022/7/27 11:59
 * @Description:
 * @Copyright (c) 2022 http://www.hangzhou.com.cn All rights reserved.
 */

declare(strict_types=1);

namespace App\Console\Commands\Dev;

use App\Models\Api\V1\Product;
use Illuminate\Console\Command;

class SyncProducts extends Command
{
    protected $signature = 'es:sync-products {--index=products}';

    protected $description = '将商品数据同步到 Elasticsearch';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        // 获取 Elasticsearch 对象
        $es = app('es');
        Product::query()
            // 预加载 SKU 和 商品属性数据，避免 N + 1 问题
            ->with(['skus', 'properties'])
            // 使用 chunkById 避免一次性加载过多数据
            ->chunkById(100, function ($products) use ($es) {
                $this->info(sprintf('正在同步 ID 范围为 %s 至 %s 的商品', $products->first()->id, $products->last()->id));
                // 初始化请求体
                $req = ['body' => []];
                // 遍历商品
                foreach ($products as $product) {
                    // 将商品模型转为 Elasticsearch 所用的数组
                    $data = $product->toESArray();
                    $req['body'][] = [
                        'index' => [
                            // 从参数中读取索引名称
                            '_index' => $this->option('index'),
                            '_type' => '_doc',
                            '_id' => $data['id'],
                        ],
                    ];
                    $req['body'][] = $data;
                }
                try {
                    // 使用 bulk 方法批量创建
                    $es->bulk($req);
                } catch (\Exception $e) {
                    $this->error($e->getMessage());
                }
            });
        $this->info('同步完成');
    }
}
