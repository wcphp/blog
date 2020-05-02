<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Route;

class ApiRequest extends FormRequest
{
    //公共校验规则配置
    public $rules = [];

    //场景校验规则配置
    public $sceneRules = [
        /* "场景（控制器操作方法名称）"=>[
           "username"=>['required']
       ]*/
    ];

    //场景配置，不配置全部校验
    public $scenes = [
        /* "场景（控制器操作方法名称）"=>["字段","username","user_id"]*/
    ];

    //公共验证错误的自定义消息配置
    public $messages = [];

    //场景验证错误的自定义消息配置（存在覆盖公共错误的自定义消息）
    public $sceneMessages = [
       /* "场景（控制器操作方法名称）"=>[
            "username.string"=>'用户名格式错误'
        ]*/
    ];

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }
    /**
     * 获取验证错误的自定义消息
     *
     * @return array
     */
    final public function messages()
    {
        $scene = $this->getScene();
        $messages = empty($this->messages) ? [] : $this->messages;
        $sceneMessages = empty($scene) ? [] : (empty($this->sceneMessages[$scene]) ? [] : $this->sceneMessages[$scene]);
        return array_merge($messages,$sceneMessages);
    }

    /**
     * 场景（控制器操作方法名称）
     *
     * @return string
     */
    final public function getScene()
    {
        return Route::current()->getActionMethod();
    }

    /**
     * 校验规则
     *
     * @return array
     */
    final public function rules()
    {
        $scene = $this->getScene();
        if(!empty($this->sceneRules[$scene])){
            $sceneRules = $this->sceneRules[$scene];
            foreach($sceneRules as $key=>$item){
                $this->rules[$key] = $item;
            }
        }
        $resRules = [];
        if(empty($this->rules)){
            return $resRules;
        }
        //场景不为空、对应场景配置存在
        if(!empty($scene && isset(($this->scenes)[$scene]))){
            foreach(($this->scenes)[$scene] as $item) {
                if(isset($this->rules[$item])){
                    $resRules[$item] = $this->rules[$item];
                }
            }
        }else{
            $resRules = $this->rules;
        }
        return $resRules;
    }
}
