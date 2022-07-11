<?php
/*
 * @Author     : lixiaoyun
 * @Email      : 120235331@qq.com
 * @Github     : http://www.github.com/siaoynli
 * @Date       : 2022/7/8 9:13
 * @Description: 异常重写
 * @Copyright (c) 2022 http://www.hangzhou.com.cn All rights reserved.
 */


declare(strict_types=1);

namespace App\Exceptions;


use Illuminate\Auth\AuthenticationException;
use Illuminate\Contracts\Support\Responsable;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\Exceptions\ThrottleRequestsException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Router;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Throwable;

class Handler extends ExceptionHandler
{

    /**
     * 异常类型列表及其相应的自定义日志级别。
     * @var array
     */
    protected $levels = [
        //
    ];

    /**
     * 不需要报告的异常类型列表。
     * @var array
     */
    protected $dontReport = [
        //
    ];

    /**
     * 验证异常时不验证的参数。
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * @Author: lixiaoyun
     * @Email: 120235331@qq.com
     * @Date: 2022/7/8 12:44
     * @Description: 为应用程序注册异常处理回调。
     * @return void
     */
    public function register(): void
    {
        $this->reportable(function (Throwable $e) {
            //
        });
    }


    /**
     * @Author: lixiaoyun
     * @Email: 120235331@qq.com
     * @Date: 2022/7/7 17:08
     * @Description: 增加http_not_found
     * @param $request
     * @param Throwable $e
     * @return JsonResponse|RedirectResponse|\Illuminate\Http\Response|mixed|Response
     * @throws \ReflectionException
     */
    public function render($request, Throwable $e): mixed
    {
        if (method_exists($e, 'render') && $response = $e->render($request)) {
            return Router::toResponse($request, $response);
        }

        if ($e instanceof Responsable) {
            return $e->toResponse($request);
        }

        $e = $this->prepareException($this->mapException($e));

        if ($response = $this->renderViaCallbacks($request, $e)) {
            return $response;
        }

        return match (true) {
            $e instanceof HttpResponseException => $e->getResponse(),
            $e instanceof AuthenticationException => $this->unauthenticated($request, $e),
            $e instanceof ValidationException => $this->convertValidationExceptionToResponse($e, $request),
            $e instanceof NotFoundHttpException => $this->httpNotFound($request, $e),
            $e instanceof ThrottleRequestsException => $this->throttleRequestsException($request, $e),

            default => $this->renderExceptionResponse($request, $e),
        };
    }


    /**
     * @Author: lixiaoyun
     * @Email: 120235331@qq.com
     * @Date: 2022/7/7 16:35
     * @Description:  判断返回json格式需要的条件
     * @param $request
     * @param Throwable $e
     * @return bool
     */
    protected function shouldReturnJson($request, Throwable $e): bool
    {
        return $request->expectsJson() || $request->is("api/*");
    }


    /**
     * @Author: lixiaoyun
     * @Email: 120235331@qq.com
     * @Date: 2022/7/7 17:08
     * @Description: 页面不存在提示信息
     * @param $request
     * @param NotFoundHttpException $exception
     * @return JsonResponse|\Illuminate\Http\Response|Response
     */
    protected function httpNotFound($request, NotFoundHttpException $exception): \Illuminate\Http\Response|JsonResponse|Response
    {
        return $this->shouldReturnJson($request, $exception)
            ? responseJsonMessage('Http Not Found', 1, $exception->getStatusCode())
            : $this->prepareResponse($request, $exception);
    }

    /**
     * @Author: lixiaoyun
     * @Email: 120235331@qq.com
     * @Date: 2022/7/11 16:38
     * @Description: 请求次数过多
     * @param $request
     * @param ThrottleRequestsException $exception
     * @return JsonResponse
     */
    protected function throttleRequestsException($request, ThrottleRequestsException $exception): JsonResponse
    {
        return responseJsonMessage("请求次数超过系统限制！", 1, $exception->getStatusCode());
    }


    /**
     * @Author: lixiaoyun
     * @Email: 120235331@qq.com
     * @Date: 2022/7/7 17:04
     * @Description: 登陆失效
     * @param $request
     * @param AuthenticationException $exception
     * @return JsonResponse|RedirectResponse|Response
     */
    protected function unauthenticated($request, AuthenticationException $exception): JsonResponse|Response|RedirectResponse
    {
        return $this->shouldReturnJson($request, $exception)
            ? responseJsonMessage($exception->getMessage(), 1, 401)
            : redirect()->guest($exception->redirectTo() ?? route('login'));
    }


    /**
     * @Author: lixiaoyun
     * @Email: 120235331@qq.com
     * @Date: 2022/7/7 16:44
     * @Description: 重写表单校验第一条错误信息
     * @param $request
     * @param ValidationException $exception
     * @return JsonResponse
     */
    protected function invalidJson($request, ValidationException $exception): JsonResponse
    {
        return responseJsonMessage($exception->validator->errors()->first(), 1, $exception->status);

    }


}
