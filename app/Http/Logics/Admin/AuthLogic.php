<?php
// +----------------------------------------------------------------------
// |
// +----------------------------------------------------------------------
// | Author: cc <iweika@aliyun.com>
// +----------------------------------------------------------------------
// | Date: 2020/03/28
// +----------------------------------------------------------------------
// | Time: 21:57
// +----------------------------------------------------------------------

namespace App\Http\Logics\Admin;

use App\Http\Logics\BaseLogic;
use App\Http\Models\AdminModel;
use App\Http\Models\NodeModel;
use App\Http\Models\RoleModel;
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
    public function loginCheck($params)
    {
        $adminRow = AdminModel::query()->where('username',$params['username'])->first();
        //echo get_admin_pwd($params['passwd'],$adminRow['salt']);exit;
        if(!$adminRow || (get_admin_pwd($params['passwd'],$adminRow['salt']) != $adminRow['passwd'])){
            return $this->returnFormat(3001,'手机号或者密有误');
        }elseif($adminRow['status'] != 1){
            return $this->returnFormat(3002,'此用户已被禁用');
        }elseif($adminRow['is_reset'] == 1){
            return $this->returnFormat(3003,'需要重置密码');
        }
        //角色权限 权限   menu树
        if($adminRow['is_super'] != 1 && empty($adminRow['role_id'])){
            return $this->returnFormat(3004,'未分配角色，请联系管理');
        }
        $roleNode = $this->getRoleNode($adminRow['role_id'],$adminRow['is_super']==1);

        if(!is_array($roleNode)){
            return $this->returnFormat(3005,$roleNode);
        }
        $adminSave = [];
        [
            'admin_id'=>$adminSave['admin_id'],
            'username'=>$adminSave['username'],
            'nickname'=>$adminSave['nickname'],
            'mobile'=>$adminSave['mobile'],
            'is_super'=>$adminSave['is_super'],
        ] = $adminRow;
        $adminSave = array_merge($adminSave,$roleNode);
        if(($res = $this->loginSave($adminSave))=== true){
            return $this->returnFormat(1000,'SUCCESS',$adminSave);
        }else{
            return $this->returnFormat(3005,$res);
        }
    }


    /**
     * 获取角色节点
     *
     * @return mixed
     */
    public function getRoleNode($roleId,$isSuper=false)
    {
        if(empty($roleId) && !$isSuper){
            return '未分配角色,请联系管理';
        }
        $nodeModel = NodeModel::query();
        if(!$isSuper){
            $roleRow = RoleModel::query()->where('role_id',$roleId)->first(['nodes','status','name']);
            if(empty($roleRow)){
                return '你的角色不存在,请联系管理';
            }
            if($roleRow['status'] != 1){
                return '你的角色已禁用,请联系管理';
            }
            $roleNodes = json_decode($roleRow['nodes'],true);
            if(empty($roleNodes)){
                return '未分配权限,请联系管理';
            }
            $nodeModel->whereIn('node_id',$roleNodes);
        }
        $nodeList = $nodeModel->where('status',1)
            ->orderByDesc('sort')
            ->get(['node_id as id','pid','name','node_tag','type','is_show','menu_style'])
            ->toArray();
        if(empty($nodeList)){
            return '未分配权限,请联系管理';
        }

        $roleRodes = array_values(array_unique(array_column($nodeList,'node_tag')));

        $menuNodeList = array_filter($nodeList,function ($node){
            return $node['type'] == 1 && $node['is_show'] == 1;
        });
        $roleMenu = $this->getMenuNodeTree($menuNodeList);

        return [
            'role_name'=>$isSuper == 1 ? '超级管理员' : $roleRow['name'],
            'power_node'=>$roleRodes,
            'menu_tree'=>$roleMenu
        ];
    }

    /**
     *
     * 菜单节点树
     * @return array
     */
    protected function getMenuNodeTree($nodes,$menuNode=[])
    {
        if(empty($menuNode)){
            foreach($nodes as $key=>$node){
                if($node['pid']==0 && $node['is_show'] == 1){
                    unset($nodes[$key]);
                    $menuNode[] = $this->getMenuNodeTree($nodes,$node);
                }
            }
        }else{
            $menuNode['child'] = [];
            foreach($nodes as $key=>$node){
                if($node['pid']==$menuNode['id'] && $node['is_show'] == 1){
                    unset($nodes[$key]);
                    $node = $this->getMenuNodeTree($nodes,$node);
                    $menuNode['child'][] = $node;
                }
            }
        }
        return $menuNode;
    }



    /**
     * 登陆态保存
     * @return mixed
     * @throws \Exception
     */
    private function loginSave($loginData)
    {
        $token =  md5(time().json_encode($loginData));
        $cacheConf = get_cache_conf($token,'login_state');
        Cache::delete($cacheConf['key']);
        if(!Cache::add($cacheConf['key'],$loginData,$cacheConf['ttl'])){
            return '登陆失败，系统异常';
        }
        foreach($loginData as $key=>$item){
            $GLOBALS[$key] = $item;
        }
        return true;
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
