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

}
