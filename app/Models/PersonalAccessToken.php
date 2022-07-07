<?php

/*
 * @Author     : lixiaoyun
 * @Email      : 120235331@qq.com
 * @Github     : http://www.github.com/siaoynli
 * @Date       : 2022/7/7 15:25
 * @Description:  重写PersonalAccessToken
 * @Copyright (c) 2022 http://www.hangzhou.com.cn All rights reserved.
 */

declare (strict_types=1);

namespace App\Models;

use Laravel\Sanctum\PersonalAccessToken as SanctumPersonalAccessToken;

class PersonalAccessToken  extends  SanctumPersonalAccessToken
{


}
