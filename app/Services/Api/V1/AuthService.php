<?php
declare (strict_types=1);

namespace App\Services\Api\V1;

use App\Exceptions\InvalidException;
use App\Models\Api\V1\User;
use App\Utils\RSA;
use Exception;
use Illuminate\Database\Eloquent\Model;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class AuthService
{

    private array $devices = ["ios", "android", "pc", "wechat"];

    /**
     * @Author: lixiaoyun
     * @Email: 120235331@qq.com
     * @Date: 2022/7/26 10:20
     * @Description: 获取解密后的密码字符串
     * @param $password
     * @return string
     * @throws Exception
     */
    public function getPassword($password): string
    {
        try {
            return $password ? RSA::decrypt($password) : "";
        } catch (Exception $e) {
            throw InvalidException::withMessage($e->getMessage());
        }
    }

    /**
     * @Author: lixiaoyun
     * @Email: 120235331@qq.com
     * @Date: 2022/7/26 15:41
     * @Description:
     * @param $field
     * @param $value
     * @return Model|null
     */
    public function getUserByField($field, $value): Model|null
    {
        return User::withTrashed()->where($field, $value)->first();
    }

    /**
     * @Author: lixiaoyun
     * @Email: 120235331@qq.com
     * @Date: 2022/7/26 10:25
     * @Description: 用户登陆后
     * @param $user
     * @return void
     */
    public function userLogin($user): void
    {
        $user->login_ip = get_client_ip();
        $user->login_count = $user->login_count + 1;
        $user->login_error_count = 0;
        $user->login_time = now();
        $user->save();
    }

    /**
     * @Author: lixiaoyun
     * @Email: 120235331@qq.com
     * @Date: 2022/7/26 10:39
     * @Description:
     * @param $user
     * @return void
     * @throws Exception
     */
    public function checkUserStatus($user): void
    {

        if (!$user || $user->deleted_at) {
            throw InvalidException::withMessage("用户名密码错误");
        }
        if ($user->status == -1 || $user->login_error_count >= 5) {
            throw InvalidException::withMessage("密码输入错误次数过多，账户已经被锁定");
        }

        if ($user->is_admin == 0) {
            throw InvalidException::withMessage("账号异常，请联系管理员");
        }

    }

    /**
     * @Author: lixiaoyun
     * @Email: 120235331@qq.com
     * @Date: 2022/7/26 10:44
     * @Description: 返回设备名
     * @param string $device_name
     * @return string
     */
    public function getDriverName(string $device_name): string
    {
        return !in_array($device_name, $this->devices) ? 'pc' : $device_name;
    }

    /**
     * @Author: lixiaoyun
     * @Email: 120235331@qq.com
     * @Date: 2022/7/26 11:08
     * @Description: 生成二维码
     * @param $randomStr
     * @return mixed
     */
    public function generateQRCode($randomStr): mixed
    {
        return QrCode::size(265)->generate(route("authenticate.wechat") . '?q=' . $randomStr)->toHtml();
    }


}
