<?php

namespace App\Http\Requests\Api\V1;

use Illuminate\Foundation\Http\FormRequest;

class BaseRequest extends FormRequest
{

    protected string $model = "";
    protected int $id = 0;

    public function __construct()
    {

        $this->id = $this->route() ? $this->route($this->route()->parameterNames[0]) : 0;
        if (method_exists($this, 'setModel')) {
            $this->setModel();
        }
        parent::__construct();
    }

    /**
     * @Author: lixiaoyun
     * @Email: 120235331@qq.com
     * @Date: 2022/7/11 17:02
     * @Description:
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @Author: lixiaoyun
     * @Email: 120235331@qq.com
     * @Date: 2022/7/11 17:04
     * @Description: 获取验证器错误的自定义属性
     * @return array
     */
    public function attributes(): array
    {
        return [

        ];
    }

    /**
     * @Author: lixiaoyun
     * @Email: 120235331@qq.com
     * @Date: 2022/7/11 17:09
     * @Description: 返回postRules,PutRules
     * @return array
     * @throws \Exception
     */
    public function rules(): array
    {
        $methodName = strtolower($this->method()) . 'Rules';
        if (!method_exists($this, $methodName)) {
            throw  new \Exception(__CLASS__ . '：method ' . $methodName . " does not exist");
        }
        return $this->setRules($this->$methodName());
    }


    /**
     * @Author: lixiaoyun
     * @Email: 120235331@qq.com
     * @Date: 2022/7/11 17:04
     * @Description: 获取验证器错误的自定义消息。
     * @return array
     */
    public function messages(): array
    {
        return [];
    }

    /**
     * @Author: lixiaoyun
     * @Email: 120235331@qq.com
     * @Date: 2022/7/11 17:19
     * @Description:循环rules，把校验条件重新赋值
     * @param array $rules
     * @return array
     */
    private function setRules(array $rules): array
    {
        if ($this->get("_id", 0)) {
            $_rule = [];
            foreach (array_keys($this->all()) as $value) {
                if (array_key_exists($value, $rules)) {
                    $_rule[$value] = $rules[$value];
                }
            }
            return $_rule;
        }
        return $rules;
    }
}
