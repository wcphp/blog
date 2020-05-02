<?php
// +----------------------------------------------------------------------
// | 应用控制器基类
// +----------------------------------------------------------------------
// | Author: weika <iweika@aliyun.com>
// +----------------------------------------------------------------------
// | Date: 2020/03/24
// +----------------------------------------------------------------------
// | Time: 16:25
// +----------------------------------------------------------------------

namespace App\Http\Controllers;


use App\Events\ActionLog;
use App\Http\Controllers\Controller;
use App\Utils\CodeUtil;
use Illuminate\Http\Request;

class BaseController extends Controller
{
    public $params;
    public function __construct(Request $request)
    {
        $currentPath = $request->path();
        if (!in_array($currentPath, ['login', 'logout'])) {
            if (empty($GLOBALS['landlord_id'])) {
                $this->error(CodeUtil::LANDLORD_DATA_MISS, '房东信息不存在');
            }
        }
    }

    //标记需要加操作日志
    public $addActionLog = true;

    //操作日志
   /* private function  actionLog($responseData){
        $logData = [
            'uri'=>request()->path(),
            'method'=>request()->method(),
            'params'=>request()->all(),
            'response'=>$responseData,
        ];
        event(new ActionLog($logData));
    }*/

    /**
     * 返回成功
     *
     * @param string $message
     * @param array  $data
     *
     * @return object
     */
    protected function success($message = 'SUCCESS', $data = [], $status = '')
    {
        all_to_string($data);
        $data = [
            'code' => CodeUtil::SUCCESS,
            'msg'  =>  $message ? $message : 'SUCCESS',
            'data' => empty($data) ? (object)[] : $data,
        ];
        if (!empty($status)) {
            $data['status'] = $status;
        }

        $response = response()->json($data);
        /*if($this->addActionLog){
            $this->actionLog($response->original);
        }*/
        return $response;
    }

    /**
     * 返回失败
     *
     * @param string $message
     * @param array  $data
     *
     * @return object
     */
    protected function error($code, $message = '', $data = [], $status = '')
    {
        all_to_string($data);
        if (!$message) {
            $message = trim(__('err.' . $code));
        }

        if (!$message) {
            $message = 'error';
        }

        $data = [
            'code' => (int)$code,
            'msg'  => CodeUtil::getMessage($code,$message),
            'data' => empty($data) ? (object)[] : $data,
        ];

        if (!empty($status)) {
            $data['status'] = $status;
        }
        $response = response()->json($data);
        /*if($this->addActionLog){
            $this->actionLog($response->original);
        }*/
        return $response;
    }

    //校验并获取房东ID
    protected function checkLandlordId(&$params=''){
        if(empty($GLOBALS['landlord_id'])){
            $this->error(CodeUtil::LANDLORD_DATA_MISS, '房东信息不存在');
        }
        if(is_array($params)){
            $params['landlord_id'] = $GLOBALS['landlord_id'];
        }else{
            $params = $GLOBALS['landlord_id'];
        }
        return $params;
    }

    /***
     * 获取房东id
     * @return mixed
     */
    public function getLandlordId()
    {
        if(empty($GLOBALS['landlord_id'])){
            $this->error(CodeUtil::LANDLORD_DATA_MISS, '房东信息不存在');
        }
        return $GLOBALS['landlord_id'];
    }

    /***
     * 获取参数
     * @param array $default
     * @param Request $request
     * @return array
     */
    public function getParams($request, $default = []){
        $params = $request->toArray();
        if (!empty($default) && is_array($default)) {
            foreach ($default as $k => $v) {
                if (!isset($params[$k]) || empty($params[$k])) {
                    $params[$k] = $v;
                }
            }
        }
        if (!isset($params['pageSize'])) {
            $params['pageSize'] = 10;
        }
        if (!isset($params['page'])) {
            $params['page'] = 1;
        }
        if (!isset($params['landlord_id']) && isset($GLOBALS['landlord_id'])) {
            $params['landlord_id'] = $GLOBALS['landlord_id'];
        }
        unset($params['s']);
        unset($params['token']);
        return $params;
    }

}
