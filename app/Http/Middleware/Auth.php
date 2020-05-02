<?php
// +----------------------------------------------------------------------
// | 后台权限校验
// +----------------------------------------------------------------------
// | Author: cc <iweika@aliyun.com>
// +----------------------------------------------------------------------
// | Date: 2020/03/25
// +----------------------------------------------------------------------
// | Time: 14:17
// +----------------------------------------------------------------------

namespace App\Http\Middleware;


use App\Utils\CodeUtil;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Response;


class Auth
{
    /**
     * 登录和权限校验
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     *
     * @return mixed
     * @throws \Exception
     */
    public function handle($request, \Closure $next)
    {
        $token = $request->input('token', '');
        if (empty($token)) {
            $data = [
                'code' => CodeUtil::PARAMS_VALIDATE_FAIL,
                'msg'  => 'Token不能为空',
                'data' => [],
            ];
            return Response::json($data);
        }

        $cacheConf = get_cache_conf($token,'login_state');

        $loginStateDate = Cache::get($cacheConf['key']);
        //dump($loginStateDate);exit;
        if(empty($loginStateDate)){
            $data = [
                'code' => CodeUtil::LOGIN_TOKEN_FAIL,
                'msg'  => '系统登录态已过期，请重新登陆',
                'data' => [],
            ];
            return Response::json($data);
        }
        foreach($loginStateDate as $key=>$item){
            $GLOBALS[$key] = $item;
        }

        $current_rule_data = $request->route()->getAction();
        $current_node = isset($current_rule_data['as']) ? $current_rule_data['as'] : '';
        /*if(empty($response['code']) || $response['code'] != 1){
            $data = [
                'code' => 402,
                'msg'  => '没有权限，请联系管理员授权',
                'data' => [],
            ];
            return Response::json($data);
        }*/
        return $next($request);

    }

}
