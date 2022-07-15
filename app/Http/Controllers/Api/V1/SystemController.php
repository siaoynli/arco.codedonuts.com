<?php

namespace App\Http\Controllers\Api\V1;


use Illuminate\Http\Request;

class SystemController extends BaseController
{
    public function clearCache(Request $request)
    {
        newCache("api")->flush();
        return successResponseData([], "操作成功");
    }
}
