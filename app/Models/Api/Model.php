<?php
/*
 * @Author     : lixiaoyun
 * @Email      : 120235331@qq.com
 * @Github     : http://www.github.com/siaoynli
 * @Date       : 2022/7/8 9:13
 * @Description: Model基础
 * @Copyright (c) 2022 http://www.hangzhou.com.cn All rights reserved.
 */


declare(strict_types=1);

namespace App\Models\Api;


use App\Models\Api\V1\User;
use Illuminate\Database\Eloquent\Model as EloquentModel;

class Model extends EloquentModel
{

    /**
     * @Author: lixiaoyun
     * @Email: 120235331@qq.com
     * @Date: 2022/7/26 9:05
     * @Description: id倒叙
     * @param $query
     * @return mixed
     */
    public function scopeRecent($query): mixed
    {
        return $query->orderBy('id', 'desc');
    }

    /**
     * @Author: lixiaoyun
     * @Email: 120235331@qq.com
     * @Date: 2022/7/26 9:05
     * @Description: id正序
     * @param $query
     * @return mixed
     */
    public function scopeOlder($query): mixed
    {
        return $query->orderBy('id', 'asc');
    }


    /**
     * @Author: lixiaoyun
     * @Email: 120235331@qq.com
     * @Date: 2022/7/26 9:06
     * @Description: 用户id
     * @param $query
     * @param User $user
     * @return mixed
     */
    public function scopeByUser($query, User $user): mixed
    {
        return $query->where('user_id', $user->id);
    }

    /**
     * @Author: lixiaoyun
     * @Email: 120235331@qq.com
     * @Date: 2022/7/26 17:09
     * @Description:
     * @param $query
     * @return mixed
     */
    public function scopeActive($query): mixed
    {
        return $query->where('status', 1);
    }

    /**
     * @Author: lixiaoyun
     * @Email: 120235331@qq.com
     * @Date: 2022/7/26 17:09
     * @Description: 查询$days天内注册的用户
     * @param $query
     * @param $days
     * @return mixed
     */
    public function scopeRegisteredWithinDays($query, $days): mixed
    {
        return $query->where('created_at', '>=', now()->subDays($days));
    }

    /**
     * @Author: lixiaoyun
     * @Email: 120235331@qq.com
     * @Date: 2022/7/26 17:11
     * @Description: 查询今天的记录
     * @param $query
     * @return mixed
     */
    public function scopeToday($query): mixed
    {
        return $query->whereDate('created_at', now());
    }


    /**
     * @Author: lixiaoyun
     * @Email: 120235331@qq.com
     * @Date: 2022/7/26 17:11
     * @Description: 永不更新name字段
     * @param $value
     * @return void
     */
    public function setNameAttribute($value): void
    {
        if ($this->name) {
            return;
        }
        $this->attributes['name'] = $value;
    }

}
