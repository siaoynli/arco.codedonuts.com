<?php

namespace App\Http\Resources;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Support\Str;
use JsonSerializable;

class UserResource extends ApiResource
{
    /**
     * @Author: lixiaoyun
     * @Email: 120235331@qq.com
     * @Date: 2022/7/26 17:39
     * @Description:
     * @param $request
     * @return array|JsonSerializable|Arrayable
     */
    public function toArray($request): array|JsonSerializable|Arrayable
    {
        $arr = parent::toArray($request);
        $arr["phone"] = Str::mask($arr["phone"], '*', 3, 5);
        return $arr;
    }
}
