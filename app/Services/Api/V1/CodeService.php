<?php
declare (strict_types=1);

namespace App\Services\Api\V1;

use App\Exceptions\InvalidException;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use Psr\SimpleCache\InvalidArgumentException;

class CodeService
{

    /**
     * @Author: lixiaoyun
     * @Email: 120235331@qq.com
     * @Date: 2022/7/26 12:04
     * @Description:
     * @param string $phone
     * @param string $cacheKey
     * @return void
     * @throws ContainerExceptionInterface
     * @throws InvalidException
     * @throws NotFoundExceptionInterface
     * @throws InvalidArgumentException
     */
    public function limitCode(string $phone, string $cacheKey): void
    {
        if (newCache("api")->has($cacheKey)) {
            //每天单个手机号码限制发送短信次数
            if (newCache("api")->get($cacheKey) >= 10) {
                throw InvalidException::withMessage("该手机号码发送短信超过单日限制,请联系管理员");
            }
            newCache("api")->increment($cacheKey, 1);
        } else {
            newCache("api")->put($cacheKey, 1, diffSecondsToMorn());
        }

    }

    /**
     * @Author: lixiaoyun
     * @Email: 120235331@qq.com
     * @Date: 2022/7/26 12:04
     * @Description:
     * @param string $cacheKey
     * @return void
     * @throws ContainerExceptionInterface
     * @throws InvalidException
     * @throws NotFoundExceptionInterface
     */
    public function throttleCode(string $cacheKey): void
    {
        $value = newCache("api")->get($cacheKey);
        if ($value) {
            $interval_time = $value - time();
            if ($interval_time > 0) {
                throw InvalidException::withMessage("该手机号码已经发过短信，请过" . $interval_time . '秒后再试');
            }
        }
    }

    /**
     * @Author: lixiaoyun
     * @Email: 120235331@qq.com
     * @Date: 2022/7/26 12:02
     * @Description:
     * @param int $length
     * @return string
     */
    public function generateCode(int $length = 6): string
    {
        return str_pad((string)rand(1000, 999999), $length, "0", STR_PAD_LEFT);
    }

}
