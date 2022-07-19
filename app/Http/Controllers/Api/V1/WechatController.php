<?php

namespace App\Http\Controllers\Api\V1;


use App\Http\Controllers\Controller;
use App\Models\User;
use EasyWeChat\Kernel\Exceptions\BadRequestException;
use EasyWeChat\Kernel\Exceptions\InvalidArgumentException;
use EasyWeChat\Kernel\Exceptions\RuntimeException;
use EasyWeChat\OfficialAccount\Application;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use Psr\Http\Message\ResponseInterface;
use ReflectionException;
use Throwable;


class WechatController extends Controller
{

    /**
     * @Author: lixiaoyun
     * @Email: 120235331@qq.com
     * @Date: 2022/7/19 15:16
     * @Description: 获取app对象
     * @return Application
     * @throws Exception
     */
    public function getApp()
    {


        $config = [
            'app_id' => 'wxfe8052d5aa86cbf6',
            'secret' => 'f87e4a4d617baec0e8e35d35ce59171a',
            'token' => 'yhoABKY5VUSaRjBsUxjW82d',
            'aes_key' => 'kooQ0mRgSIP4noB2ALaBDU9qeOhEGayuFqyZ4cKiO8N',
            'oauth' => [
                'scopes' => ['snsapi_userinfo'],
                'callback' => route("wechat.oauth_back"),
            ],
        ];
        try {
            return new Application($config);
        } catch (InvalidArgumentException $e) {
            throw  new Exception($e->getMessage());
        }
    }

    /**
     * @Author: lixiaoyun
     * @Email: 120235331@qq.com
     * @Date: 2022/7/19 15:16
     * @Description: 服务端验证
     * @return ResponseInterface
     * @throws InvalidArgumentException
     * @throws BadRequestException
     * @throws RuntimeException
     * @throws ReflectionException
     * @throws Throwable
     */
    public function serve()
    {
        $server = $this->getApp()->getServer();
        $server->addEventListener('subscribe', function ($message, \Closure $next) {
            return '感谢您关注微信公众号!';
        });
        return $server->serve();
    }

    /**
     * @Author: lixiaoyun
     * @Email: 120235331@qq.com
     * @Date: 2022/7/19 15:38
     * @Description: 获取微信授权
     * @param Request $request
     * @return \Illuminate\Contracts\Foundation\Application|RedirectResponse|Redirector
     * @throws InvalidArgumentException
     */
    public function oauth_code(Request $request)
    {
        $callback = $request->get("callback", "");
        $redirect_uri = $callback ? route("wechat.oauth_back", ["callback" => $callback]) : route("wechat.oauth_back");
        $app = $this->getApp();
        $redirectUrl = $app->getOAuth()->scopes(['snsapi_userinfo'])->redirect($redirect_uri);
        return redirect($redirectUrl);
    }

    /**
     * @Author: lixiaoyun
     * @Email: 120235331@qq.com
     * @Date: 2022/7/19 15:42
     * @Description: 获取用户
     * @param Request $request
     * @return mixed
     * @throws ContainerExceptionInterface
     * @throws InvalidArgumentException
     * @throws NotFoundExceptionInterface
     */
    public function oauth_back(Request $request)
    {

        $code = $request->get("code", "");

        $app = $this->getApp();

        $user = $app->getOAuth()->userFromCode($code);

        return User::where("wx_openid", $user->id)->first();

    }
}
