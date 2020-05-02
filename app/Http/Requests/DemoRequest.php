<?php
// +----------------------------------------------------------------------
// | 表单验证
// +----------------------------------------------------------------------
// | Author: cc <iweika@aliyun.com>
// +----------------------------------------------------------------------
// | Date: 2020/03/24
// +----------------------------------------------------------------------
// | Time: 15:37
// +----------------------------------------------------------------------
namespace App\Http\Requests;

class DemoRequest extends ApiRequest
{
    /*
    * 常用规则：
    * required-字段必须存在于输入数据中，而不是空 值为 null 值为空字符串、值为空数组或空 Countable 对象、值为无路径的上传文件
    * numeric-数字
    * integer-整数
    * in-区间
    * date- 根据 PHP strtotime 函数，验证的字段必须是有效的日期。
    * date_format:Y-m-d H:i:s  指定日期格式
    * nullable-验证字段可以为 null。这在验证基本数据类型时特别有用，例如可以包含空值的字符串和整数
    * exclude_unless:【字段】,【指定值】  【字段】值等于指定【值】才进行校验此字段
    * exclude_if:【字段】,【bool值】      【字段】值等于【bool值】才进行校验此字段
    * string-字符串
    * not_regex:pattern 验证字段必须与给定的正则表达式不匹配   不能是数子串-'not_regex:/^[0-9]+$/'
    * regex:pattern 验证字段必须与给定的正则表达式匹配
    * */
    public $rules = [

    ];

    public $messages = [

    ];
    //场景配置，场景名称为控制器操作方法名称
    public $scenes = [

    ];
    /**
     * 获取验证错误的自定义属性
     *
     * @return array
     */
    public function attributes()
    {
        return [
            //'email' => 'email address',
        ];
    }

}
