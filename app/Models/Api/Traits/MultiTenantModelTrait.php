<?php

/*
 * @Author     : lixiaoyun
 * @Email      : 120235331@qq.com
 * @Github     : http://www.github.com/siaoynli
 * @Date       : 2022/7/26 17:22
 * @Description:
 * @Copyright (c) 2022 http://www.hangzhou.com.cn All rights reserved.
 */

declare(strict_types=1);

namespace App\Models\Api\Traits;

trait MultiTenantModelTrait
{
    /**
     * @Author: lixiaoyun
     * @Email: 120235331@qq.com
     * @Date: 2022/7/26 17:24
     * @Description: 创建时候自动调用
     * @return void
     */
    public static function bootMultiTenantModelTrait(): void
    {
        static::creating(function ($model) {
            if (property_exists($model, 'user_id')) {
                $model->user_id = auth()->id();
            }
        });
    }
}
