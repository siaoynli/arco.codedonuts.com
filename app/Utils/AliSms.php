<?php

/*
 * @Author     : lixiaoyun
 * @Email      : 120235331@qq.com
 * @Github     : http://www.github.com/siaoynli
 * @Date       : 2022/7/11 9:58
 * @Description:
 * @Copyright (c) 2022 http://www.hangzhou.com.cn All rights reserved.
 */

declare(strict_types=1);

namespace App\Utils;

use JetBrains\PhpStorm\ArrayShape;

class AliSms
{

    const  DEFAULT_PRODUCT_NAME = "杭州网";

    const  DEFAULT_SMS_SINGLE_NAMES = ["身份验证", "注册验证", "登录验证", "变更验证", "活动验证"];
    //
    //验证码${code}，您正在尝试变更${product}重要信息，请妥善保管账户信息。
    const  DEFAULT_SMS_TEMPLATE_CHANGE = "SMS_69010031";

    //验证码${code}，您正在尝试修改${product}登录密码，请妥善保管账户信息。
    const  DEFAULT_SMS_TEMPLATE_PASSWORD = "SMS_69010032";

    //验证码${code}，您正在参加${product}的${item}活动，请确认系本人申请。
    const  DEFAULT_SMS_TEMPLATE_ACTIVITY = "SMS_69010033";

    //验证码${code}，您正在注册成为${product}用户，感谢您的支持！。
    const  DEFAULT_SMS_TEMPLATE_REGISTER = "SMS_69010034";

    //验证码${code}，您正尝试异地登录${product}，若非本人操作，请勿泄露。
    const  DEFAULT_SMS_TEMPLATE_SIGNIN = "SMS_69010035";

    //验证码${code}，您正在登录${product}，若非本人操作，请勿泄露。
    const  DEFAULT_SMS_TEMPLATE_LOGIN = "SMS_69010036";

    //验证码${code}，您正在进行${product}身份验证，打死不要告诉别人哦！
    const  DEFAULT_SMS_TEMPLATE_AUTH = "SMS_69010038";


    /**
     * @Author: lixiaoyun
     * @Email: 120235331@qq.com
     * @Date: 2022/7/11 10:00
     * @Description:
     * @param string $code
     * @return array
     */
    #[ArrayShape(['template' => "string", 'data' => "string[]"])] public static function codeMessage(string $code): array
    {
        return [
            'template' => self::DEFAULT_SMS_TEMPLATE_AUTH,
            'data' => [
                "code" => $code,
                "product" => self::DEFAULT_PRODUCT_NAME,
            ],
        ];
    }


}
