<?php

/*
 * @Author     : lixiaoyun
 * @Email      : 120235331@qq.com
 * @Github     : http://www.github.com/siaoynli
 * @Date       : 2022/7/26 12:39
 * @Description:
 * @Copyright (c) 2022 http://www.hangzhou.com.cn All rights reserved.
 */

declare(strict_types=1);

namespace App\Jobs\Traits;

use App\Models\Api\V1\QueueLog;

trait QueueLogs
{

    /**
     * @Author: lixiaoyun
     * @Email: 120235331@qq.com
     * @Date: 2022/7/26 12:35
     * @Description: ${CARET}
     * @param string $message
     * @param string $response
     * @param int $status
     * @return void
     */
    public function createQueueLogs(string $message, string $response, int $status = 1): void
    {
        $data["message"] = $message;
        $data["status"] = $status;
        $data["response"] = $response;
        $data["class_name"] = __CLASS__;
        QueueLog::create($data);
    }

}
