<?php
declare(strict_types=1);

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;


class AuthenticateController extends Controller
{

    private array $devices = ["ios", "android", "pc", "wechat"];

    /**
     * @Author: lixiaoyun
     * @Email: 120235331@qq.com
     * @Date: 2022/7/8 9:34
     * @Description: 登陆接口
     * @param Request $request
     * @return JsonResponse
     */
    public function login(Request $request)
    {
        $user = User::where('id', 1)->first();
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

        return responseJsonData(["token" => $token, "expire_at" => $expire_at]);
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
