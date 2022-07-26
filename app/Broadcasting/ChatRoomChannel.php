<?php

namespace App\Broadcasting;

use App\Models\Api\V1\User;

class ChatRoomChannel
{
    /**
     * Create a new channel instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * @Author: lixiaoyun
     * @Email: 120235331@qq.com
     * @Date: 2022/7/22 17:05
     * @Description: 判断用户是否在房间，这里简单用1，2表示，自己根据chatID来做逻辑
     * @param User $user
     * @param $chatId
     * @return bool
     */
    public function join(User $user, $chatId): bool
    {
        return in_array($user->id, [1, 2]);

    }
}
