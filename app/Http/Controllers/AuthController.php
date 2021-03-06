<?php
// +----------------------------------------------------------------------
// | 认证
// +----------------------------------------------------------------------
// | Author: weika <iweika@aliyun.com>
// +----------------------------------------------------------------------
// | Date: 2020/03/28
// +----------------------------------------------------------------------
// | Time: 21:30
// +----------------------------------------------------------------------

namespace App\Http\Controllers\System;

use App\Utils\CodeUtil;
use App\Http\Controllers\BaseController;
use App\Http\Requests\System\AuthRequest;
use App\Logics\System\AuthLogic;
use Illuminate\Support\Facades\Cache;


class AuthController extends BaseController
{
    /**
     * 登陆
     * @param int
     * @param boolean
     * @param string
     *
     * @return object
     */
    public function login(AuthRequest $request,AuthLogic $authLogic)
    {
        $params = $request->validated();
        $res = $authLogic->login($params);
        if(is_array($res)){
            return $this->success('登陆成功', $res);
        }else{
            return $this->error(CodeUtil::ACTION_ERROR, $res);
        }
    }

    /**
     * 登出
     * @param int
     * @param boolean
     * @param string
     *
     * @return array
     * @throws \Exception
     * @throws \Psr\SimpleCache\InvalidArgumentException
     */
    public function logout(AuthRequest $request)
    {
        $token = $request->input('token');
        $cacheConf = get_cache_conf($token,'login_state');
        Cache::delete($cacheConf['key']);
        return $this->success('退出系统成功');
    }

    /**
     * 修改自己密码
     * @param int
     * @param boolean
     * @param string
     *
     * @return object
     * @throws \Exception
     * @throws \Psr\SimpleCache\InvalidArgumentException
     */
    public function editPasswd(AuthRequest $request,AuthLogic $authLogic)
    {
        $params = $request->validated();
        $res = $authLogic->editOwnPasswd($params['old_passwd'],$params['new_passwd']);
        if($res === true){
            return $this->success();
        }
        return $this->error(CodeUtil::ACTION_ERROR, $res);
    }

    /**
     * 登陆后的登陆信息
     *
     * @return object
     * @throws \Exception
     * @throws \Psr\SimpleCache\InvalidArgumentException
     */
    public function loginInfo(AuthRequest $request)
    {
        $token = $request->get('token');
        $cacheConf = get_cache_conf($token,'login_state');
        $loginStateDate = Cache::get($cacheConf['key']);
        if(!empty($loginStateDate)){
            return $this->success('SUCCESS',$loginStateDate);
        }
        return $this->error(CodeUtil::ACTION_ERROR, '获取登陆信息失败');
    }

}
