<?php
// +----------------------------------------------------------------------
// |
// +----------------------------------------------------------------------
// | Author: weika <iweika@aliyun.com>
// +----------------------------------------------------------------------
// | Date: 2020/03/28
// +----------------------------------------------------------------------
// | Time: 21:57
// +----------------------------------------------------------------------

namespace App\Logics\System;


use App\Logics\BaseLogic;
use App\Models\System\AdminModel;
use App\Models\System\LandlordAccountModel;
use Illuminate\Support\Facades\Cache;

class AuthLogic extends BaseLogic
{
    /**
     *
     * @param int
     * @param boolean
     * @param string
     *
     * @return mixed
     */
    public function login($params)
    {
        $adminRow = AdminModel::where('phone',$params['phone'])->where('is_plat',2)->first();

        if(!$adminRow || (get_admin_pwd($params['passwd'],$adminRow['salt']) != $adminRow['passwd'])){
            return '手机号或者密有误';
        }elseif($adminRow['account_status'] != 1){
            return '此用户已被禁用';
        }
        return $this->loginSave($adminRow);
    }

    /**
     * 登陆态保存
     * @return mixed
     * @throws \Exception
     */
    private function loginSave($adminData)
    {
        $loginData = [
            'admin_id'      => $adminData['admin_id'],
            'landlord_id'   => $adminData['landlord_id'],
            'role_id'       => $adminData['role_id'],
            'realname'      => $adminData['realname'],
            'token'         => md5(time().json_encode($adminData)),
            'rolename'      => $adminData['role_id'] == 1 ? '房东' : '子账号',
            ];
        if(empty($adminData['landlord_id'])){
            return '此账户未绑定房东，请联系管理员处理';
        }
        $cacheConf = get_cache_conf($loginData['token'],'login_state');
        Cache::delete($cacheConf['key']);
        if(!Cache::add($cacheConf['key'],$loginData,$cacheConf['ttl'])){
            return '登陆失败，系统异常';
        }
        foreach($loginData as $key=>$item){
            $GLOBALS[$key] = $item;
        }
        return $loginData;
    }

    /**
     * 修改自己密码
     *
     * @return mixed
     */
    public function editOwnPasswd($oldPasswd,$newPasswd)
    {
        $adminRow = AdminModel::where('admin_id',$GLOBALS['admin_id'])->first();
        if(empty($adminRow)){
            return '登陆态失效';
        }
        if(!$adminRow || (get_admin_pwd($oldPasswd,$adminRow['salt']) != $adminRow['passwd'])){
            return '旧密码错误';
        }
        $adminRow['salt'] = random_str(8);
        $adminRow['passwd'] = get_admin_pwd($newPasswd,$adminRow['salt']);
        if($adminRow->save()){
            return true;
        }
        return '保存数据失败';
    }
}
