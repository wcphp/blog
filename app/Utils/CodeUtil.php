<?php
// +----------------------------------------------------------------------
// | 提示码
// +----------------------------------------------------------------------
// | Author: cc <iweika@aliyun.com>
// +----------------------------------------------------------------------
// | Date: 2020/03/24
// +----------------------------------------------------------------------
// | Time: 15:37
// +----------------------------------------------------------------------

namespace App\Utils;

/**
 * @property string SUCCESS
 */
class CodeUtil
{
    const SUCCESS = 1;//成功
    const SYSTEM_ERROR = 1111;//系统错误
    const PARAMS_VALIDATE_FAIL = 2000;//参数校验失败
    const ACTION_ERROR = 2001;// 操作失败

    const LOGIN_TOKEN_FAIL = -1;//登陆态校验失败
    const NOT_ACCESS_RIGHT = -2;//没有权限访问
    const MUST_RESET_PASSWD= -3;//需要重置密码

    //生产环境提示对应记录
    protected static $productMessageList = [
        self::SUCCESS    => 'SUCCESS',//请求成功
        self::SYSTEM_ERROR => '系统错误,请稍后重试',
        self::LOGIN_TOKEN_FAIL => '系统登录态已过期，请重新登陆',
        self::NOT_ACCESS_RIGHT => '你没有访问权限，请联系管理员',
        self::MUST_RESET_PASSWD => '需要重置密码',
    ];

    /**
     * 获取对应提示信息
     * @access public
     * @param string $code 错误编码
     * @param string $devMsg 开发错误提示
     * @return
     */
    public static function getMessage($code, $devMsg = '')
    {
        $prMsg = '';

        if (in_array($code, self::$productMessageList)) {
            $prMsg = self::$productMessageList[$code];
        }
        if(env('APP_DEBUG') && !empty($prMsg) && !empty($devMsg)){
            $prMsg =  $prMsg .'【'.$devMsg.'】';
        }elseif(empty($prMsg)){
            $prMsg = $devMsg;
        }
        return $prMsg;

    }


}
