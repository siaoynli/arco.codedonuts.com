<?php

namespace App\Http\Controllers\Api\V1;


use App\Http\Requests\Api\V1\VerificationCodesRequest;
use App\Jobs\AliSmsQueue;
use App\Utils\AliSms;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use Psr\SimpleCache\InvalidArgumentException;

class CodeController extends BaseController
{

    /**
     * @Author: lixiaoyun
     * @Email: 120235331@qq.com
     * @Date: 2022/7/11 16:23
     * @Description: 发送验证码
     * @param VerificationCodesRequest $request
     * @return JsonResponse
     * @throws ContainerExceptionInterface
     * @throws InvalidArgumentException
     * @throws NotFoundExceptionInterface
     */
    public function send(VerificationCodesRequest $request)
    {

        $phone = $request->phone;

        $day_limit_key = "day_limit_" . $phone;

        if (newCache("api")->has($day_limit_key)) {
            //每天单个手机号码限制发送短信次数
            if (newCache("api")->get($day_limit_key) >= 10) {
                return failResponseData("该手机号码发送短信超过单日限制,请联系管理员");
            }
            newCache("api")->increment($day_limit_key, 1);
        } else {
            newCache("api")->put($day_limit_key, 1, diffSecondsToMorn());
        }

        //手机号码分钟频率限制
        $send_limit_key = 'send_limit_' . $phone;
        $value = newCache("api")->get($send_limit_key);
        if ($value) {
            $interval_time = $value - time();
            if ($interval_time > 0) {
                return failResponseData("该手机号码已经发过短信，请过" . $interval_time . '秒后再试');
            }
        }
        //生成验证码
        $code = str_pad((string)rand(1000, 999999), 6, "0", STR_PAD_LEFT);

        //加密
        $key = 'code_' . Str::random(15);
        //10分钟过期
        $expireAt = now()->addMinutes(10);

        newCache("api")->put($key, ['phone' => $phone, "code" => $code], $expireAt);

        //手机号码分钟频率限制
        newCache("api")->put($send_limit_key, time() + 60, now()->addSeconds(60));

        //后台发送验证码
        dispatch(new AliSmsQueue((int)$phone, AliSms::codeMessage($code)))->onQueue("sms");

        return successResponseData(["key" => $key, "expireAt" => $expireAt->toDateTimeString()]);

    }

}
