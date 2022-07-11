<?php
/*
 * @Author     : lixiaoyun
 * @Email      : 120235331@qq.com
 * @Github     : http://www.github.com/siaoynli
 * @Date       :  2021/7/7 14:18
 * @Description:  辅助函数文件
 * @Copyright (c) 2022 http://www.hangzhou.com.cn All rights reserved.
 */

declare(strict_types=1);

use Illuminate\Http\JsonResponse;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Cache;

/**
 * @Author: lixiaoyun
 * @Email: 120235331@qq.com
 * @Date: 2022/7/7 14:18
 * @Description: 返回响应信息
 * @return string
 */
function ping(): string
{
    return "it's works ok!";
}

/**
 * @Author: lixiaoyun
 * @Email: 120235331@qq.com
 * @Date: 2022/7/11 16:13
 * @Description: 缓存添加标签
 * @return \Illuminate\Cache\CacheManager|\Illuminate\Contracts\Foundation\Application|mixed|void
 */
function newCache()
{
    $arguments = func_get_args();
    if (empty($arguments)) {
        return cache();
    }
    cache()->tag((string)$arguments[0]);
}

/**
 * @Author: lixiaoyun
 * @Email: 120235331@qq.com
 * @Date: 2022/7/8 9:23
 * @Description: 获取bearerToken
 * @return string|null
 */
function getAuthorizationToken(): string|null
{
    return request()->bearerToken();

}

/**
 * @Author: lixiaoyun
 * @Email: 120235331@qq.com
 * @Date: 2022/7/7 17:11
 * @Description: 返回json格式信息
 * @param string $message
 * @param int $error
 * @param int $code
 * @return JsonResponse
 */
function responseJsonMessage(string $message, int $error = 1, int $code = 200): JsonResponse
{
    return response()->json(["message" => $message, "error" => $error], $code);
}

/**
 * @Author: lixiaoyun
 * @Email: 120235331@qq.com
 * @Date: 2022/7/7 17:12
 * @Description: 返回数据列表
 * @param array $data
 * @return JsonResponse
 */
function responseJsonDataList(array $data = []): JsonResponse
{
    return response()->json(["data" => $data, "error" => 0]);
}

/**
 * @Author: lixiaoyun
 * @Email: 120235331@qq.com
 * @Date: 2022/7/8 9:41
 * @Description: 返回json格式数据
 * @param array $data
 * @return JsonResponse
 */
function responseJsonData(array $data = [], $code = 201): JsonResponse
{
    return response()->json(array_merge($data, ["error" => 0]), $code);
}

/**
 * @Author: lixiaoyun
 * @Email: 120235331@qq.com
 * @Date: 2022/7/11 16:14
 * @Description: 计算现在到00：00时间差
 * @return float|int
 */
function diffSecondsToMorn(): float|int
{
    $date = date('Y-m-d', strtotime('+1 day'));
    $carbon = Carbon::parse($date);
    return Carbon::now()->diffInSeconds($carbon, false);
}


/**
 * @Author: lixiaoyun
 * @Email: 120235331@qq.com
 * @Date: 2022/7/11 16:57
 * @Description: 获取真实ip
 * @return mixed|string
 */
function get_client_ip(): mixed
{
    $ip = 'unknown';
    if (getenv('HTTP_CLIENT_IP') && strcasecmp(getenv('HTTP_CLIENT_IP'), 'unknown')) {
        $ip = getenv('HTTP_CLIENT_IP');
    } elseif (getenv('HTTP_X_FORWARDED_FOR') && strcasecmp(getenv('HTTP_X_FORWARDED_FOR'), 'unknown')) {
        $ip = getenv('HTTP_X_FORWARDED_FOR');
    } elseif (getenv('REMOTE_ADDR') && strcasecmp(getenv('REMOTE_ADDR'), 'unknown')) {
        $ip = getenv('REMOTE_ADDR');
    } elseif (isset($_SERVER['REMOTE_ADDR']) && $_SERVER['REMOTE_ADDR'] && strcasecmp($_SERVER['REMOTE_ADDR'], 'unknown')) {
        $ip = $_SERVER['REMOTE_ADDR'];
    }
    return preg_match('/[\d\.]{7,15}/', $ip, $matches) ? $matches [0] : '';
}


/**
 * @Author: lixiaoyun
 * @Email: 120235331@qq.com
 * @Date: 2022/7/7 14:45
 * @Description: 数据加密解密
 * @param $string
 * @param string $operation
 * @param string $key
 * @param int $expiry
 * @param int $key_length
 * @return string
 */
function sys_auth($string, string $operation = 'ENCODE', string $key = '', int $expiry = 0, int $key_length = 4): string
{
    $string = trim($string);
    if ($operation == 'ENCODE') {
        $string = urlencode($string);
    }

    $key = md5($key != '' ? $key : 'sWI8ugjr7DRRFhP6OUgRpv5QUkxTzGoKoCpwS4lr1gQ');
    $fixedkey = md5($key);
    $egiskeys = md5(substr($fixedkey, 16, 16));
    $runtokey = $key_length ? ($operation == 'ENCODE' ? substr(md5((string)microtime(true)), -$key_length) : substr($string, 0, $key_length)) : '';
    $keys = md5(substr($runtokey, 0, 16) . substr($fixedkey, 0, 16) . substr($runtokey, 16) . substr($fixedkey, 16));
    $string = $operation == 'ENCODE' ? sprintf('%010d', $expiry ? $expiry + time() : 0) . substr(md5($string . $egiskeys), 0, 16) . $string : base64_decode(substr($string, $key_length));


    $result = '';
    $string_length = strlen($string);
    for ($i = 0; $i < $string_length; $i++) {
        $result .= chr(ord($string[$i]) ^ ord($keys[$i % 32]));
    }
    if ($operation == 'ENCODE') {
        return $runtokey . base64_encode($result);
    } else {
        if ((substr($result, 0, 10) == 0 || substr($result, 0, 10) - time() > 0) && substr($result, 10, 16) == substr(md5(substr($result, 26) . $egiskeys), 0, 16)) {
            return urldecode(substr($result, 26));
        } else {
            return '';
        }
    }
}

