<?php

/*
 * @Author     : lixiaoyun
 * @Email      : 120235331@qq.com
 * @Github     : http://www.github.com/siaoynli
 * @Date       : 2022/7/8 9:13
 * @Description: 用户表
 * @Copyright (c) 2022 http://www.hangzhou.com.cn All rights reserved.
 */


declare(strict_types=1);

namespace App\Models\Api\V1;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;


class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes;

    /**
     * @var string[]
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
        'avatar',
        'nick_name',
        'cn_name',
        'gender',
        'qq',
        'address',
        'login_count',
        'login_error_count',
        'login_time',
        'login_ip',
        'status',
        'remarks',
        'role_id',
        'department_id',
        'login_notification',
        'phone_verified_at',
        'wx_openid',
        'qq_openid',
        'ios_openid',
        'device_hash',
        'open_comment',
        'invite_code',
        'certification',
    ];

    //防止意外被修改
    protected $guarded = [
        'id',
        'is_admin',
    ];


    //序列化隐藏字段
    protected $hidden = [
        'password',
        'remember_token',
        'is_admin',
        'wx_openid',
        'qq_openid',
        'ios_openid',
        'device_hash',
    ];


    // 格式化数据
    protected $casts = [
        //'email_verified_at' => 'datetime',
        "open_comment" => "boolean"
    ];

    /**
     * @Author: lixiaoyun
     * @Email: 120235331@qq.com
     * @Date: 2022/7/8 9:13
     * @Description: accessToken赋值，返回token
     * @param string $device_name
     * @return string
     */
    public function getSanctumToken(string $device_name = "pc"): string
    {
        $device_name = strtolower($device_name);
        $_newAccessToken = $this->createToken($device_name);
        $this->accessToken = $_newAccessToken->accessToken;
        return $_newAccessToken->plainTextToken;
    }

    /**
     * @Author: lixiaoyun
     * @Email: 120235331@qq.com
     * @Date: 2022/7/8 9:14
     * @Description: 删除用户当前设备所有token
     * @param string $device_name
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

    /**
     * @Author: lixiaoyun
     * @Email: 120235331@qq.com
     * @Date: 2022/7/26 12:06
     * @Description: 过期时间 token创建时间+过期分钟数
     * @return mixed
     */
    public function getTokenExpireAt(): mixed
    {
        return $this->currentAccessToken()->created_at->addMinutes(config("sanctum.expiration", null))->toDateTimeString();
    }

    /**
     * @Author: lixiaoyun
     * @Email: 120235331@qq.com
     * @Date: 2022/7/26 9:08
     * @Description: 密码自动加密
     * @param $value
     * @return void
     */
    public function setPasswordAttribute($value): void
    {
        $this->attributes['password'] = password_hash($value, PASSWORD_DEFAULT);
    }
}
