<?php
// +----------------------------------------------------------------------
// | 表单验证
// +----------------------------------------------------------------------
// | Author: weika <iweika@aliyun.com>
// +----------------------------------------------------------------------
// | Date: 2020/03/24
// +----------------------------------------------------------------------
// | Time: 15:37
// +----------------------------------------------------------------------
namespace App\Http\Requests\Auth;

use App\Http\Requests\ApiRequest;

class AuthRequest extends ApiRequest
{
    /*
     * 常用规则：
     * required-字段必须存在于输入数据中，而不是空 值为 null 值为空字符串、值为空数组或空 Countable 对象、值为无路径的上传文件
     *  numeric-数字
     * integer-整数
     * in-区间
     * nullable-验证字段可以为 null。这在验证基本数据类型时特别有用，例如可以包含空值的字符串和整数
     * */
    public $rules = [
        'token'=>['required'],
        'phone'=>['required','min:11','max:11'],
        'passwd'=>['required','min:6','max:18'],
        'old_passwd'=>['required','min:6','max:18'],
        'new_passwd'=>['required','min:6','max:18'],
    ];

    public $messages = [
        'phone.required'=>'请输入登陆手机号',
        'phone.m*'=>'你输入的手机号码有误',
        'passwd.required'=>'请输入登陆密码',
        'passwd.m*'=>'登陆密码长度为6~18位',
        'token'=>'token不能为空',
        'old_passwd.required'=>'旧密码不能为空',
        'new_passwd.required'=>'新密码不能为空',
        'old_passwd.m*'=>'旧密码长度为6~18位',
        'new_passwd.m*'=>'新密码长度为6~18位',
    ];
    //场景配置，场景名称为控制器操作方法名称
    public $scenes = [
        'login'=>['phone','passwd'],
        'logout'=>['token'],
        'editPasswd'=>['old_passwd','new_passwd'],
        'loginInfo'=>['token'],
    ];



}
