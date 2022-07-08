<?php

/*
 * @Author     : lixiaoyun
 * @Email      : 120235331@qq.com
 * @Github     : http://www.github.com/siaoynli
 * @Date       : 2022/7/8 9:13
 * @Description:
 * @Copyright (c) 2022 http://www.hangzhou.com.cn All rights reserved.
 */


declare(strict_types=1);

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * @var string[]
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * @var string[]
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * @var string[]
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];


    /**
     * @Author: lixiaoyun
     * @Email: 120235331@qq.com
     * @Date: 2022/7/8 9:13
     * @Description: 获取token
     * @param string $device_name
     * @return string
     */
    public function getSanctumToken(string $device_name = "pc"): string
    {
        $device_name = strtolower($device_name);
        return $this->createToken($device_name)->plainTextToken;
    }

    /**
     * @Author: lixiaoyun
     * @Email: 120235331@qq.com
     * @Date: 2022/7/8 9:14
     * @Description: 删除用户当前设备所有token
     * @return int
     */
    public function destroySanctumTokens(string $device_name = ""): int
    {
        $device_name = strtolower($device_name);
        return $device_name ? $this->tokens()->where("name", $device_name)->delete() : $this->tokens()->delete();
    }

    /**
     * @Author: lixiaoyun
     * @Email: 120235331@qq.com
     * @Date: 2022/7/8 9:16
     * @Description: 删除用户当前token
     * @return mixed
     */
    public function destroyCurrentSanctumToken(): mixed
    {
        return $this->currentAccessToken()->delete();
    }
}
