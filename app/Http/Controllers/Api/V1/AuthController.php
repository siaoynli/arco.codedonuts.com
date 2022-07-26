<?php
declare(strict_types=1);

namespace App\Http\Controllers\Api\V1;


use App\Exceptions\InvalidException;
use App\Http\Requests\Api\V1\UserLoginRequest;
use App\Services\Api\V1\AuthService;
use App\Utils\RSA;
use Exception;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Str;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use Psr\SimpleCache\InvalidArgumentException;
use SimpleSoftwareIO\QrCode\Facades\QrCode;


class AuthController extends BaseController
{


    protected AuthService $service;

    public function __construct(AuthService $service)
    {
        $this->service = $service;
    }

    /**
     * @Author: lixiaoyun
     * @Email: 120235331@qq.com
     * @Date: 2022/7/26 12:05
     * @Description:
     * @param UserLoginRequest $request
     * @return JsonResponse
     * @throws ContainerExceptionInterface
     * @throws InvalidArgumentException
     * @throws InvalidException
     * @throws NotFoundExceptionInterface
     */
    public function login(UserLoginRequest $request)
    {

        $type = $request->get("loginType", "code");

        switch ($type) {
            case "account":
                $email = $request->email;
                //第一道解密密码
                $password = $this->service->getPassword($request->password);

                $user = $this->service->getUserByField('email', $email);

                $this->service->checkUserStatus($user);

                if (!password_verify($password, $user->password)) {
                    $user->increment('login_error_count');
                    throw InvalidException::withMessage("用户名密码错误");
                }
                break;

            default:
                //手机验证码登陆
                $code = $request->code;
                $phone = $request->phone;
                $verification_key = $request->key;
                $verifyData = newCache("api")->get($verification_key);

                if (!$verifyData) {
                    throw InvalidException::withMessage("验证码已经失效");
                }

                if (!hash_equals($verifyData["code"], $code)) {
                    throw InvalidException::withMessage("验证码错误");
                }
                if (!hash_equals($verifyData["phone"], $phone)) {
                    throw InvalidException::withMessage("手机号码不是验证码发送的手机号码");
                }
                $user = $this->service->getUserByField('phone', $phone);

                $this->service->checkUserStatus($user);
                //删除key
                newCache("api")->delete($verification_key);
                break;
        }


        $this->service->userLogin($user);

        $device_name = $this->service->getDriverName($request->get("device_name", "pc"));

        //同设备其他地方登陆的token删除
        $user->destroySanctumTokens($device_name);
        //获取token
        $token = $user->getSanctumToken($device_name);
        //显示过期时间
        $expire_at = $user->getTokenExpireAt();

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
        $user = array_merge($request->user()->toArray(), ["role" => "admin"]);
        return successResponseData($user);
    }

    /**
     * @Author: lixiaoyun
     * @Email: 120235331@qq.com
     * @Date: 2022/7/15 11:25
     * @Description: 发送公钥,公钥缓存1周
     * @return JsonResponse
     * @throws Exception
     */
    public function publicKey()
    {
        $cacheKey = $this->getCacheKey();

        $keys = newCache("api")->remember("publicKey_" . $cacheKey, now()->addDays(7), function () {
            return Rsa::generate();
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

    /**
     * @Author: lixiaoyun
     * @Email: 120235331@qq.com
     * @Date: 2022/7/19 14:27
     * @Description: 生成二维码
     * @return void
     */
    public function qrcode(Request $request)
    {
        $randomStr = $request->get("q", "");
        if (!$randomStr) {
            $randomStr = Str::random(64);
            newCache("api")->put($randomStr, 1, now()->addMinutes(1));
        }
        return QrCode::size(265)->generate(route("authenticate.wechat") . '?q=' . $randomStr);
    }


    /**
     * @Author: lixiaoyun
     * @Email: 120235331@qq.com
     * @Date: 2022/7/21 11:11
     * @Description: 获取二维码链接和检查授权有没有成功链接
     * @return JsonResponse
     */
    public function getQrcode()
    {
        $randomStr = Str::random(64);
        //等待扫码
        newCache("api")->put($randomStr, ["status" => -2], now()->addSeconds(65));
        $qrcode = $this->service->generateQRCode($randomStr);
        $checkUri = '/checkTicket?q=' . $randomStr;
        return successResponseData(["qrcode" => $qrcode, 'checkUri' => $checkUri]);
    }


    /**
     * @Author: lixiaoyun
     * @Email: 120235331@qq.com
     * @Date: 2022/7/21 11:12
     * @Description: 扫码后跳转到微信授权
     * @param Request $request
     * @return Application|RedirectResponse|Redirector
     * @throws ContainerExceptionInterface
     * @throws InvalidException
     * @throws NotFoundExceptionInterface
     */
    public function wechat(Request $request)
    {
        $q = $request->get("q", "");
        $value = newCache('api')->get($q);
        if (!$value) {
            throw InvalidException::withMessage("链接已经失效，请刷新页面重试!");
        }
        //如果值为0，就是扫码后请求授权中
        newCache("api")->put($q, ["status" => 0], now()->addSeconds(65));
        //返回微信链接
        $authUrl = route("wechat.oauth_code", ["callback" => $q]);
        return redirect($authUrl);
    }

    /**
     * @Author: lixiaoyun
     * @Email: 120235331@qq.com
     * @Date: 2022/7/21 11:12
     * @Description: 微信授权成功会把用户信息存储到q里面，客户端获取到信息，说明授权成功
     * @param Request $request
     * @return JsonResponse
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     * @throws Exception
     */
    public function checkTicket(Request $request)
    {
        $q = $request->get("q", "");
        $value = newCache('api')->get($q);
        if (!$value) {
            throw InvalidException::withMessage("页面已经失效，请刷新页面重试!");
        }
        return successResponseData($value);
    }


}
