<?php
// +----------------------------------------------------------------------
// | 应用控制器基类
// +----------------------------------------------------------------------
// | Author: cc <iweika@aliyun.com>
// +----------------------------------------------------------------------
// | Date: 2020/03/24
// +----------------------------------------------------------------------
// | Time: 16:25
// +----------------------------------------------------------------------

namespace App\Http\Controllers\Admin;


//use App\Events\ActionLog;
use App\Http\Controllers\Controller;
use App\Utils\CodeUtil;
use Illuminate\Http\Request;

class BaseAdmin extends Controller
{
    public $params;

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

}
