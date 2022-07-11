<?php

namespace App\Http\Requests\Api\V1;


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
        // TODO: Implement setModel() method.
    }

    /**
     * @Author: lixiaoyun
     * @Email: 120235331@qq.com
     * @Date: 2022/7/11 17:11
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
            "login_type" => ["required", "in:code,account"],
            "email" => ["required"],
            "password" => ["required"],
            "phone_number" => ["required", new PhoneNumberRule()],
            "code" => ["required"],
            "key" => ["required"],
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
        $rules = [];
        return parent::setRules($rules);;
    }

    /**
     * @Author: lixiaoyun
     * @Email: 120235331@qq.com
     * @Date: 2022/7/11 17:40
     * @Description:
     * @return array
     */
    public function attributes(): array
    {
        return array_merge(parent::attributes(),
            [
                'key' => '验证码Key',
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
    public function message(): array
    {
        return [
            "login_type.required" => "登陆类型必须填写",
        ];
    }
}
