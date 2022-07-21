<?php

/*
 * @Author     : lixiaoyun
 * @Email      : 120235331@qq.com
 * @Github     : http://www.github.com/siaoynli
 * @Date       : 2022/7/12 15:39
 * @Description:   RSA加密解密
 * @Copyright (c) 2022 http://www.hangzhou.com.cn All rights reserved.
 */

declare(strict_types=1);

namespace App\Utils;

use Exception;
use Illuminate\Support\Facades\Cache;
use JetBrains\PhpStorm\ArrayShape;

class RSA
{

    private static array $dn = array(
        "countryName" => "GB",
        "stateOrProvinceName" => "SomeWhoami",
        "localityName" => "Whoami",
        "organizationName" => "The Room Whoami",
        "organizationalUnitName" => "PHP Documentation Team",
        "commonName" => "Whoami",
        "emailAddress" => "Whoami@example.com"
    );

    private static array $config = array(
        //指定应该使用多少位来生成私钥  512 1024  2048  4096等
        "private_key_bits" => 1024,
        //选择在创建CSR时应该使用哪些扩展。可选值有 OPENSSL_KEYTYPE_DSA, OPENSSL_KEYTYPE_DH, OPENSSL_KEYTYPE_RSA 或 OPENSSL_KEYTYPE_EC. 默认值是 OPENSSL_KEYTYPE_RSA.
        "private_key_type" => OPENSSL_KEYTYPE_RSA,
    );


    public static string $prefix = "openssl_";

    /**
     * @Author: lixiaoyun
     * @Email: 120235331@qq.com
     * @Date: 2022/7/12 16:08
     * @Description:
     * @param string $privatePass
     * @param int $days
     * @return array
     * @throws Exception
     */
    #[ArrayShape(['publicKey' => "mixed", 'privateKey' => "mixed"])] public static function generate(string $privatePass = '', int $days = 365): array
    {
        if (!extension_loaded('openssl')) {
            throw new Exception('openssl extension does not exist');
        }
        $privatePass = $privatePass ?: sha1(env('APP_KEY'));

        //生成证书
        $privkey = openssl_pkey_new(self::$config);
        $csr = openssl_csr_new(self::$dn, $privkey);
        $userCert = openssl_csr_sign($csr, null, $privkey, $days);
        //导出证书$csrKey
        openssl_x509_export($userCert, $csrKey);
        //导出密钥$privateKey
        openssl_pkcs12_export($userCert, $privateKey, $privkey, $privatePass);
        //获取私钥
        openssl_pkcs12_read($privateKey, $certs, $privatePass);

        //    获取公钥
        $pub_key = openssl_pkey_get_public($csrKey);
        $keyData = openssl_pkey_get_details($pub_key);

        $private = $certs['pkey'];
        $public = $keyData['key'];

        $arr = array('publicKey' => $public, 'privateKey' => $private);

        Cache::tags("rsa")->forever(self::$prefix . $privatePass, $arr);
        return $arr;
    }


    /**
     * @Author: lixiaoyun
     * @Email: 120235331@qq.com
     * @Date: 2022/7/12 15:40
     * @Description: 加密
     * @param string $str
     * @param string $privatePass
     * @param bool $need_base64_encode
     * @return string
     * @throws Exception
     */
    public static function encrypt(string $str, string $privatePass = '', bool $need_base64_encode = true): string
    {

        $privatePass = $privatePass ?: sha1(env('APP_KEY'));

        $arr = Cache::tags("rsa")->get(self::$prefix .$privatePass, false);
        if (!$arr) {
            throw new Exception("没有获取到密钥");
        }
        $public_key = $arr['publicKey'];
        $encrypt_data = '';
        //加密
        try {
            openssl_public_encrypt($str, $encrypt_data, $public_key);
            //加密后可以base64_encode后方便在网址中传输或者打印否则打印为乱码
            if ($need_base64_encode) {
                $encrypt_data = base64_encode($encrypt_data);
            }
        } catch (Exception $e) {
            throw  new Exception($e->getMessage());
        }
        return $encrypt_data;
    }

    /**
     * @Author: lixiaoyun
     * @Email: 120235331@qq.com
     * @Date: 2022/7/12 15:41
     * @Description: 解密
     * @param string $str
     * @param string $privatePass
     * @param bool $need_base64_decode
     * @return string
     * @throws Exception
     */
    public static function decrypt(string $str, string $privatePass = '', bool $need_base64_decode = true): string
    {
        $privatePass = $privatePass ?: sha1(env('APP_KEY'));

        $arr = Cache::tags("rsa")->get('openssl_' .$privatePass, false);
        if (!$arr) {
            throw new Exception("没有获取到密钥");
        }
        $private_key = $arr["privateKey"];
        $decrypt_data = '';
        $encrypt_data = $str;
        if ($need_base64_decode) {
            $encrypt_data = base64_decode($encrypt_data);
        }
        //解密
        try {
            openssl_private_decrypt($encrypt_data, $decrypt_data, $private_key, OPENSSL_PKCS1_PADDING);
        } catch (Exception $e) {
            throw  new Exception($e->getMessage());
        }

        return $decrypt_data;
    }


}
