<?php

/*
 * @Author     : lixiaoyun
 * @Email      : 120235331@qq.com
 * @Github     : http://www.github.com/siaoynli
 * @Date       : 2022/7/27 11:29
 * @Description:
 * @Copyright (c) 2022 http://www.hangzhou.com.cn All rights reserved.
 */

declare(strict_types=1);

namespace App\Console\Commands\Dev\Indexs;

use Illuminate\Support\Facades\Artisan;

class ProjectIndex implements IndexInterface
{

    public static function getAliasName(): string
    {
        return 'products';
    }


    public static function getProperties(): array
    {
        return [
            'type' => ['type' => 'keyword'],
            'title' => ['type' => 'text', 'analyzer' => 'ik_smart', 'search_analyzer' => 'ik_smart_synonym'],
            'long_title' => ['type' => 'text', 'analyzer' => 'ik_smart', 'search_analyzer' => 'ik_smart_synonym'],
            'category_id' => ['type' => 'integer'],
            'category' => ['type' => 'keyword'],
            'category_path' => ['type' => 'keyword'],
            'description' => ['type' => 'text', 'analyzer' => 'ik_smart'],
            'price' => ['type' => 'scaled_float', 'scaling_factor' => 100],
            'on_sale' => ['type' => 'boolean'],
            'rating' => ['type' => 'float'],
            'sold_count' => ['type' => 'integer'],
            'review_count' => ['type' => 'integer'],
            'skus' => [
                'type' => 'nested',
                'properties' => [
                    'title' => [
                        'type' => 'text',
                        'analyzer' => 'ik_smart',
                        'search_analyzer' => 'ik_smart_synonym',
                    ],
                    'description' => ['type' => 'text', 'analyzer' => 'ik_smart'],
                    'price' => ['type' => 'scaled_float', 'scaling_factor' => 100],
                ],
            ],
            'properties' => [
                'type' => 'nested',
                'properties' => [
                    'name' => ['type' => 'keyword'],
                    'value' => ['type' => 'keyword'],
                    'search_value' => ['type' => 'keyword'],
                ],
            ],
        ];
    }

    public static function getSettings(): array
    {
        return [
            'analysis' => [
                'analyzer' => [
                    'ik_smart_synonym' => [
                        'type' => 'custom',
                        'tokenizer' => 'ik_smart',
                        'filter' => ['synonym_filter'],
                    ],
                ],
                'filter' => [
                    'synonym_filter' => [
                        'type' => 'synonym',
                        //analysis/synonyms.txt ???????????????/usr/share/elasticsearch/config?????????
                        'synonyms_path' => 'analysis/synonyms.txt',
                    ],
                ],
            ],
        ];
    }


    public static function rebuild($indexName): void
    {
        // ?????? Artisan ?????? call ??????????????????????????????
        // call ?????????????????????????????????????????????????????????????????????
        Artisan::call('es:sync-products', ['--index' => $indexName]);
    }
}
