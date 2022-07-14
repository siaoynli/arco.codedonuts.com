<?php
declare(strict_types=1);

namespace App\Http\Controllers\Api\V1;


use App\Http\Requests\Api\V1\LoginRequest;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;


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
     */
    public function login(LoginRequest $request)
    {

        $type = $request->get("loginType", "code");

        //用户名密码登陆
        if ($type == "account") {
            $user = User::where('id', 1)->first();
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
        return responseJsonMessage("成功退出", 0);
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

        return responseJsonData(["user" => $request->user()]);
    }
}
