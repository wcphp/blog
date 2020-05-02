<?php
// +----------------------------------------------------------------------
// | 逻辑基类
// +----------------------------------------------------------------------
// | Author: weika <iweika@aliyun.com>
// +----------------------------------------------------------------------
// | Date: 2020/03/26
// +----------------------------------------------------------------------
// | Time: 0:16
// +----------------------------------------------------------------------

namespace App\Logics;


class BaseLogic
{
    /**
     * 返回固定的code
     *
     * @param        $code
     * @param string $message
     * @param array  $data
     *
     * @return array
     */
    protected function returnFormat($code, $message = '', $data = [])
    {
        return ['code' => $code, 'info' => $message, 'data' => $data];
    }
}
