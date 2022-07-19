<?php

namespace App\Http\Controllers\Api\V1;


use App\Http\Controllers\Controller;
use EasyWeChat\OfficialAccount\Application;


class WechatController extends Controller
{


    public function getToken()
    {
        return 'yhoABKY5VUSaRjBsUxjW82d';
    }

    public function oauth_code()
    {

        $config = [
            'app_id' => 'wxfe8052d5aa86cbf6',
            'secret' => 'f87e4a4d617baec0e8e35d35ce59171a',
            'token' => 'yhoABKY5VUSaRjBsUxjW82d',
            'aes_key' => 'ql0PT1X8KDeC3hZ85c7IyXeRYTCnYHtFaeMUdR6weEM' // 明文模式请勿填写 EncodingAESKey
            //...
        ];

        $app = new Application($config);
    }


    public function oauth_back()
    {

    }
}
