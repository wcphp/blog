<?php

namespace App\Exceptions;

use App\Utils\CodeUtil;
use Exception;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\Exceptions\ThrottleRequestsException;
use Illuminate\Validation\UnauthorizedException;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
     */
    protected $dontFlash = [
        'password',
        'password_confirmation',
    ];

    /**
     * Report or log an exception.
     *
     * @param  \Exception  $exception
     * @return void
     *
     * @throws \Exception
     */
    public function report(Exception $exception)
    {
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Exception  $exception
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @throws \Exception
     */
    public function render($request, Exception $exception)
    {
        $data = [
            'code' => CodeUtil::SYSTEM_ERROR,
            'msg'=> CodeUtil::getMessage(CodeUtil::SYSTEM_ERROR),
            'data' => (object)[],
        ];

        if (!($exception instanceof ValidationException)
            && !($exception instanceof UnauthorizedException)
            && !($exception instanceof  AuthenticationException)
            && !($exception instanceof  ThrottleRequestsException)
            && !($exception instanceof  MethodNotAllowedHttpException)
        ) {
            //调试模式展示异常
            if(env('APP_DEBUG', false)){
                return parent::render($request, $exception);
            }
        } else {
            //参数验证错误
            if ($exception instanceof ValidationException) {
                $data['code'] = CodeUtil::PARAMS_VALIDATE_FAIL;
                $data['msg'] = $exception->validator->getMessageBag()->first();

                //登陆认证失败
            }elseif ($exception instanceof UnauthorizedException) {
                $data['code'] = CodeUtil::NOT_ACCESS_RIGHT;
                $data['msg'] = CodeUtil::getMessage(CodeUtil::NOT_ACCESS_RIGHT);

                //登陆认证失败
            }elseif ($exception instanceof AuthenticationException) {
                $data['code'] = CodeUtil::NOT_ACCESS_RIGHT;
                $data['msg'] = CodeUtil::getMessage(CodeUtil::NOT_ACCESS_RIGHT);

                //访问频次限制
            }elseif ($exception instanceof ThrottleRequestsException) {
                $data['code'] = CodeUtil::SYSTEM_ERROR;
                $data['msg'] = '接口访问频次过快';

                //请求方式方法不允许 get post 等
            }elseif ($exception instanceof MethodNotAllowedHttpException) {
                $data['code'] = CodeUtil::SYSTEM_ERROR;
                $data['msg'] = '接口请求方式不存在';

            }
        }
        return response()->json($data);
    }
}
