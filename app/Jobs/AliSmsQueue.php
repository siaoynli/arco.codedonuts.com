<?php

/*
 * @Author     : lixiaoyun
 * @Email      : 120235331@qq.com
 * @Github     : http://www.github.com/siaoynli
 * @Date       : 2022/7/11 9:50
 * @Description:
 * @Copyright (c) 2022 http://www.hangzhou.com.cn All rights reserved.
 */

declare(strict_types=1);

namespace App\Jobs;


use App\Models\QueueLogs;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Overtrue\EasySms\EasySms;


class AliSmsQueue implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 1;

    public int $timeout = 60;

    private array $message;
    private int $phone;
    private string $sign_name;

    /**
     * @param int $phone
     * @param array $message
     * @param string $sign_name
     */
    public function __construct(int $phone, array $message, string $sign_name = "杭州网")
    {
        $this->message = $message;
        $this->phone = $phone;
        $this->sign_name = $sign_name;

    }

    /**
     * @Author: lixiaoyun
     * @Email: 120235331@qq.com
     * @Date: 2022/7/11 9:52
     * @Description: 发送短信
     * @return void
     * @throws \Exception
     */
    public function handle(): void
    {
        $config = config("easysms");
        if ($this->sign_name) {
            $config['gateways']['aliyun']['sign_name'] = $this->sign_name;
        }
        $easySms = new EasySms($config);

        $code = null;
        //如果有code代表发送验证码
        if (isset($this->message['data']['code'])) {
            $code = $this->message['data']['code'];
        }
        $sendMsg = $code == null ? "发送短信" : "发送验证码:" . $code;
        try {
            $response = $easySms->send($this->phone, $this->message, ["aliyun"]);
        } catch (\Exception $e) {
            $errMsg = $sendMsg . "到手机号码:" . $this->phone . ",失败:" . $e->getExceptions();
            throw new \Exception($errMsg);
        }


        $data["message"] = $sendMsg . "到手机号码:" . $this->phone;
        $data["status"] = 1;
        $data["response"] = json_encode($response["aliyun"]);
        $data["class_name"] = __CLASS__;
        QueueLogs::create($data);

    }

    /**
     * @Author: lixiaoyun
     * @Email: 120235331@qq.com
     * @Date: 2022/7/21 15:44
     * @Description: 失败记录
     * @param \Exception $exception
     * @return void
     */
    public function failed(\Exception $exception)
    {

        $data["message"] = $exception->getMessage();;
        $data["status"] = 0;
        $data["response"] = json_encode($this->message);
        $data["class_name"] = __CLASS__;
        QueueLogs::create($data);

    }
}
