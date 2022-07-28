<?php

/*
 * @Author     : lixiaoyun
 * @Email      : 120235331@qq.com
 * @Github     : http://www.github.com/siaoynli
 * @Date       : 2022/7/28 10:06
 * @Description:
 * @Copyright (c) 2022 http://www.hangzhou.com.cn All rights reserved.
 */

declare(strict_types=1);

namespace App\Console\Commands\Dev\Indexs;

interface IndexInterface
{
    static function getAliasName(): string;

    static function getProperties(): array;

    static function getSettings(): array;

    static function rebuild($indexName): void;
}
