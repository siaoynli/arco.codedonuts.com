<?php

/*
 * @Author     : lixiaoyun
 * @Email      : 120235331@qq.com
 * @Github     : http://www.github.com/siaoynli
 * @Date       : 2022/7/11 17:12
 * @Description:
 * @Copyright (c) 2022 http://www.hangzhou.com.cn All rights reserved.
 */

declare(strict_types=1);

namespace App\Http\Requests\Api\V1;

interface RequestInterface
{
    public function setModel(): void;

    public function rules(): array;

    public function postRules(): array;

    public function putRules(): array;

    public function attributes(): array;

    public function message(): array;
}
