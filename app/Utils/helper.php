<?php
/*
 * @Author     : lixiaoyun
 * @Email      : 120235331@qq.com
 * @Github     : http://www.github.com/siaoynli
 * @Date       :  2021/7/7 14:18
 * @Description:  辅助函数文件
 * @Copyright (c) 2022 http://www.hangzhou.com.cn All rights reserved.
 */

declare (strict_types=1);

/**
 * @Author: lixiaoyun
 * @Email: 120235331@qq.com
 * @Date: 2022/7/7 14:18
 * @Description: 返回响应信息
 * @return string
 */
function ping(): string
{
    return "it's ok!";
}


/**
 * @Author: lixiaoyun
 * @Email: 120235331@qq.com
 * @Date: 2022/7/7 14:18
 * @Description: 数据加密解密
 * @param $string
 * @param string $operation
 * @param string $key
 * @param int $expiry
 * @return string
 */
function sys_auth($string, string $operation = 'ENCODE', string $key = '', int $expiry = 0): string
{
    $string = trim($string);
    if ($operation == 'ENCODE') {
        $string = urlencode($string);
    }

    $key_length = 4;
    $key = md5($key != '' ? $key : env('APP_KEY'));
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

