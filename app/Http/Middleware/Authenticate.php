<?php

namespace App\Http\Middleware;


use Illuminate\Auth\AuthenticationException;
use Illuminate\Auth\Middleware\Authenticate as Middleware;

class Authenticate extends Middleware
{

    /**
     * @Author: lixiaoyun
     * @Email: 120235331@qq.com
     * @Date: 2022/7/7 16:40
     * @Description:
     * @param $request
     * @param array $guards
     * @return void
     * @throws AuthenticationException
     */
    protected function unauthenticated($request, array $guards): void
    {
        throw new AuthenticationException(
            '没有权限，Token已经失效.', $guards, $this->redirectTo($request)
        );
    }


    /**
     * @Author: lixiaoyun
     * @Email: 120235331@qq.com
     * @Date: 2022/7/7 16:38
     * @Description: 如果是ajax请求或者是api请求，授权失效，抛出错误信息
     * @param $request
     * @return string|void|null
     */
    protected function redirectTo($request)
    {
        if (!$this->shouldReturnJson($request)) {
            return route('login');
        }
    }

    /**
     * @Author: lixiaoyun
     * @Email: 120235331@qq.com
     * @Date: 2022/7/7 16:42
     * @Description: 判断返回json格式条件
     * @param $request
     * @return bool
     */
    protected function shouldReturnJson($request): bool
    {
        return $request->expectsJson() || $request->is("api/*");
    }

}
