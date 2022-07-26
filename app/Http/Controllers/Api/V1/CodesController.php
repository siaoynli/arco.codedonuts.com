<?php

namespace App\Http\Controllers\Api\V1;


use App\Exceptions\InvalidException;
use App\Http\Requests\Api\V1\VerificationCodesRequest;
use App\Jobs\AliSmsQueue;
use App\Services\Api\V1\CodeService;
use App\Utils\AliSms;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Str;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use Psr\SimpleCache\InvalidArgumentException;

class CodesController extends BaseController
{

    protected CodeService $service;

    public function __construct(CodeService $service)
    {
        $this->service = $service;
    }

    /**
     * @Author: lixiaoyun
     * @Email: 120235331@qq.com
     * @Date: 2022/7/11 16:23
     * @Description: 发送验证码
     * @param VerificationCodesRequest $request
     * @return JsonResponse
     * @throws ContainerExceptionInterface
     * @throws InvalidArgumentException
     * @throws InvalidException
     * @throws NotFoundExceptionInterface
     */
    public function send(VerificationCodesRequest $request)
    {

        $phone = $request->phone;

        $day_limit_key = "day_limit_" . $phone;

        $this->service->limitCode($phone, $day_limit_key);

        //手机号码分钟频率限制
        $send_limit_key = 'send_limit_' . $phone;
        $this->service->throttleCode($send_limit_key);

        //生成验证码
        $code = $this->service->generateCode();

        //加密
        $key = 'code_' . Str::random(15);
        //10分钟过期
        $expireAt = now()->addMinutes(10);

        newCache("api")->put($key, ['phone' => $phone, "code" => $code], $expireAt);

        //手机号码分钟频率限制
        newCache("api")->put($send_limit_key, time() + 60, now()->addSeconds(60));

        //后台发送验证码
        dispatch(new AliSmsQueue((int)$phone, AliSms::codeMessage($code)));

        return successResponseData(["key" => $key, "expireAt" => $expireAt->toDateTimeString()]);

    }

}
