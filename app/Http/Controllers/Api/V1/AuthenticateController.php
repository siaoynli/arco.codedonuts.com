<?php
declare(strict_types=1);

namespace App\Http\Controllers\Api\V1;


use App\Http\Requests\Api\V1\LoginRequest;
use App\Models\User;
use App\Utils\RSA;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use Psr\SimpleCache\InvalidArgumentException;


class AuthenticateController extends BaseController
{

    private array $devices = ["ios", "android", "pc", "wechat"];

    /**
     * @Author: lixiaoyun
     * @Email: 120235331@qq.com
     * @Date: 2022/7/8 9:34
     * @Description: 登陆接口
     * @param LoginRequest $request
     * @return JsonResponse
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     * @throws InvalidArgumentException
     */
    public function login(LoginRequest $request)
    {

        $type = $request->get("loginType", "code");

        //用户名密码登陆
        if ($type == "account") {
            $email = $request->email;
            //第一道解密密码
            try {
                $password = $request->password ? RSA::decrypt($request->password, $this->getCacheKey()) : "";
            } catch (Exception $e) {
                return failResponseData($e->getMessage());
            }
            $user = User::withTrashed()->where('email', $email)->first();
            if (!$user || $user->deleted_at) {
                return failResponseData("用户名密码错误");
            }

            //后台管理远解锁账户的时候注意清除缓存
            $user_error_limit = "user_login_error_" . $user->id;

            //判断缓存有没有锁定信息
            if (newCache("api")->get($user_error_limit) >= 5) {
                return failResponseData("密码输入错误次数过多，账户已经被锁定");
            }

            if ($user->status == -1 && $user->login_error_count >= 5) {
                newCache("api")->put($user_error_limit, 5, now()->addHours(24));
                return failResponseData("密码输入错误次数过多，账户已经被锁定");
            }

            if (!password_verify($password, $user->password)) {
                $user->increment('login_error_count');
                $user->save();
                //缓存自增
                if (newCache("api")->has($user_error_limit)) {
                    newCache("api")->increment($user_error_limit, 1);
                } else {
                    newCache("api")->put($user_error_limit, 1, now()->addHours(24));
                }
                return failResponseData("用户名密码错误");
            }

            $user->login_ip = get_client_ip();
            $user->login_count = $user->login_count + 1;
            $user->login_error_count = 0;
            $user->login_time = now();
            $user->save();


        } else {
            //手机验证码登陆
            $code = $request->code;
            $phone = $request->phone;
            $verification_key = $request->key;
            $verifyData = newCache("api")->get($verification_key);

            if (!$verifyData) {
                return failResponseData("验证码已经失效");
            }

            if (!hash_equals($verifyData["code"], $code)) {
                return failResponseData("验证码错误");
            }
            if (!hash_equals($verifyData["phone"], $phone)) {
                return failResponseData("手机号码不是验证码发送的手机号码");
            }

            $user = User::withTrashed()->where("phone", $phone)->first();

            //账号不存在或者被删除
            if (!$user || $user->deleted_at) {
                return failResponseData("手机号码不存在，请联系管理员");
            }

            if ($user->status == -1) {
                return failResponseData("账号已经被冻结,请联系客服人员");
            }

            if ($user->is_admin == 0) {
                return failResponseData("账号异常，请联系管理员");
            }

            $user->login_ip = get_client_ip();
            $user->login_count = $user->login_count + 1;
            $user->login_time = now();
            $user->save();

            //删除key
            newCache("api")->delete($verification_key);

        }


        $device_name = $request->get("device_name", "pc");
        if (!in_array($device_name, $this->devices)) {
            $device_name = "pc";
        }


        //同设备其他地方登陆的token删除
        $user->destroySanctumTokens($device_name);

        //获取token
        $token = $user->getSanctumToken($device_name);

        //显示过期时间，token创建时间+过期分钟数
        $expire_at = $user->currentAccessToken()->created_at->addMinutes(config("sanctum.expiration", null))->toDateTimeString();

        return successResponseData(["token" => $token, "expireAt" => $expire_at]);
    }


    /**
     * @Author: lixiaoyun
     * @Email: 120235331@qq.com
     * @Date: 2022/7/8 9:34
     * @Description: 退出
     * @param Request $request
     * @return JsonResponse
     */
    public function logout(Request $request)
    {
        $request->user()->destroyCurrentSanctumToken();
        return successResponseData([], "成功退出");
    }

    /**
     * @Author: lixiaoyun
     * @Email: 120235331@qq.com
     * @Date: 2022/7/8 9:35
     * @Description: 获取当前用户
     * @param Request $request
     * @return JsonResponse
     */
    public function current(Request $request)
    {
        return successResponseData(array_merge($request->user()->toArray(), ["role" => "admin"]));
    }

    /**
     * @Author: lixiaoyun
     * @Email: 120235331@qq.com
     * @Date: 2022/7/15 11:25
     * @Description: 发送公钥
     * @return JsonResponse
     * @throws Exception
     */
    public function publicKey()
    {
        $cacheKey = $this->getCacheKey();

        $keys = newCache("api")->remember("publicKey_" . $cacheKey, now()->addHours(1), function () use ($cacheKey) {
            return  Rsa::generate($cacheKey);
        });

        return successResponseData(["publicKey" => $keys['publicKey']]);

    }

    /**
     * @Author: lixiaoyun
     * @Email: 120235331@qq.com
     * @Date: 2022/7/15 12:52
     * @Description:  RSA加密密钥，缓存key
     * @return string
     */
    public function getCacheKey(): string
    {
        $ip = (string)ip2long(get_client_ip());
        return sha1($ip);
    }


}
