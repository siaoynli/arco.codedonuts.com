<?php

/*
 * @Author     : lixiaoyun
 * @Email      : 120235331@qq.com
 * @Github     : http://www.github.com/siaoynli
 * @Date       : 2022/7/14 14:09
 * @Description:
 * @Copyright (c) 2022 http://www.hangzhou.com.cn All rights reserved.
 */

declare(strict_types=1);

namespace App\Http\Requests\Api\V1;

use App\Rules\PhoneNumberRule;
use JetBrains\PhpStorm\ArrayShape;

class VerificationCodesRequest extends BaseRequest implements RequestInterface
{


    /**
     * @Author: lixiaoyun
     * @Email: 120235331@qq.com
     * @Date: 2022/7/14 14:13
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
     * @Date: 2022/7/14 14:13
     * @Description: ${CARET}
     * @return array
     */
    #[ArrayShape(["phone" => "array"])] public function postRules(): array
    {
        return [
            "phone" => ['required', new PhoneNumberRule],
        ];
    }

    /**
     * @Author: lixiaoyun
     * @Email: 120235331@qq.com
     * @Date: 2022/7/14 14:13
     * @Description: ${CARET}
     * @return array
     */
    public function putRules(): array
    {
        // TODO: Implement putRules() method.
    }
}
