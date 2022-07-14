<?php

namespace App\Http\Requests\Api\V1;


use App\Models\User;
use App\Rules\PhoneNumberRule;

class LoginRequest extends BaseRequest implements RequestInterface
{


    /**
     * @Author: lixiaoyun
     * @Email: 120235331@qq.com
     * @Date: 2022/7/11 17:22
     * @Description: ${CARET}
     * @return void
     */
    public function setModel(): void
    {
        $this->model=User::class;
    }

    /**
     * @Author: lixiaoyun
     * @Email: 120235331@qq.com
     * @Date: 2022/7/12 10:05
     * @Description:
     * @return array
     * @throws \Exception
     */
    public function rules(): array
    {
        return parent::rules();
    }

    /**
     * @Author: lixiaoyun
     * @Email: 120235331@qq.com
     * @Date: 2022/7/11 17:14
     * @Description: post校验
     * @return array
     */
    public function postRules(): array
    {
        return [
            "loginType" => ["required", "in:code,account"],
            "email" => ["required_if:login_type,account", "email"],
            "password" => ["required_if:login_type,account", "max:50"],
            "phone" => ["required_if:login_type,code", new PhoneNumberRule()],
            "code" => ["required_if:login_type,code", "digits:6"],
            "key" => ["required_if:login_type,code", "max:100"],
        ];

    }

    /**
     * @Author: lixiaoyun
     * @Email: 120235331@qq.com
     * @Date: 2022/7/11 17:32
     * @Description: put校验
     * @return array
     */
    public function putRules(): array
    {
        return [];

    }

    /**
     * @Author: lixiaoyun
     * @Email: 120235331@qq.com
     * @Date: 2022/7/11 17:40
     * @Description:  合并自定义错误属性名
     * @return array
     */
    public function attributes(): array
    {
        return array_merge(parent::attributes(),
            [

            ]
        );
    }


    /**
     * @Author: lixiaoyun
     * @Email: 120235331@qq.com
     * @Date: 2022/7/11 17:41
     * @Description: ${CARET}
     * @return array
     */
    public function messages(): array
    {
        return [
            "loginType.required" => "登陆类型必须指定",
            "loginType.in" => "登陆类型有误",
            "email.required_if" => "请输入邮箱",
            "password.required_if" => "请输入密码",
            "phone.required_if" => "请输入手机号码",
            "code.required_if" => "请输入验证码",
            "key.required_if" => "请输入验证码Key",
            "digits.required_if" => "验证码是6位数字",
        ];
    }
}
