<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class IdRule implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param string $attribute
     * @param mixed $value
     * @return bool
     */
    public function passes($attribute, $value): bool
    {
        if (!$this->isValidCard($value)) {
            return false;
        }
        return true;
    }

    /**
     * @Author: lixiaoyun
     * @Email: 120235331@qq.com
     * @Date: 2022/7/26 13:49
     * @Description:
     * @param $id
     * @return bool
     */
    private function isValidCard($id): bool
    {
        if (18 != strlen($id)) {
            return false;
        }
        $weight = [7, 9, 10, 5, 8, 4, 2, 1, 6, 3, 7, 9, 10, 5, 8, 4, 2];
        $code = ['1', '0', 'X', '9', '8', '7', '6', '5', '4', '3', '2'];
        $mode = 0;
        $ver = substr($id, -1);
        if ($ver == 'x') {
            $ver = 'X';
        }
        foreach ($weight as $key => $val) {
            if ($key == 17) {
                continue;
            }
            $digit = intval(substr($id, $key, 1));
            $mode += $digit * $val;
        }
        $mode %= 11;
        if ($ver != $code[$mode]) {
            return false;
        }
        list($month, $day, $year) = $this->getMDYFromCard($id);
        $check = checkdate($month, $day, $year);
        if (!$check) {
            return false;
        }
        $today = date('Ymd');
        $date = substr($id, 6, 8);
        if ($date >= $today) {
            return false;
        }
        return true;
    }

    private function getMDYFromCard($id): array
    {
        $date = substr($id, 6, 8);
        $year = substr($date, 0, 4);
        $month = substr($date, 4, 2);
        $day = substr($date, 6);
        return [$month, $day, $year];
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return ':attribute 格式错误';
    }
}
