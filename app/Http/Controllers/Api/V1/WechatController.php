<?php

namespace App\Http\Controllers\Api\V1;


use App\Http\Controllers\Controller;
use EasyWeChat\Kernel\Exceptions\InvalidArgumentException;
use EasyWeChat\OfficialAccount\Application;


class WechatController extends Controller
{

    /**
     * @Author: lixiaoyun
     * @Email: 120235331@qq.com
     * @Date: 2022/7/19 15:16
     * @Description: 获取app对象
     * @return Application
     * @throws \Exception
     */
    public function getApp()
    {
        $config = [
            'app_id' => 'wxfe8052d5aa86cbf6',
            'secret' => 'f87e4a4d617baec0e8e35d35ce59171a',
            'token' => 'yhoABKY5VUSaRjBsUxjW82d',
            'aes_key' => 'kooQ0mRgSIP4noB2ALaBDU9qeOhEGayuFqyZ4cKiO8N' // 明文模式请勿填写 EncodingAESKey
        ];
        try {
            return new Application($config);
        } catch (InvalidArgumentException $e) {
            throw  new \Exception($e->getMessage());
        }
    }

    /**
     * @Author: lixiaoyun
     * @Email: 120235331@qq.com
     * @Date: 2022/7/19 15:16
     * @Description: 服务端验证
     * @return \Psr\Http\Message\ResponseInterface
     * @throws InvalidArgumentException
     * @throws \EasyWeChat\Kernel\Exceptions\BadRequestException
     * @throws \EasyWeChat\Kernel\Exceptions\RuntimeException
     * @throws \ReflectionException
     * @throws \Throwable
     */
    public function serve()
    {
        $server = $this->getApp()->getServer();

        return $server->serve();
    }

    public function oauth_code()
    {

    }


    public function oauth_back()
    {

    }
}
