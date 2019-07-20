<?php

namespace app\admin\common\exception;

use Exception;
use think\exception\Handle;
use think\exception\ValidateException;
use think\exception\HttpException;

class Http extends Handle
{

    public function render(Exception $e)
    {

        // 参数验证错误
        if ($e instanceof ValidateException) {
            return view('index/userList', [
                'code'  => 403,
                'msg'   => $e->getError(),
            ]);
        }

        // 请求异常
        if ($e instanceof HttpException && request()->isAjax()) {
            return response($e->getMessage(), $e->getStatusCode());
        }

        //TODO::开发者对异常的操作
        //可以在此交由系统处理
        return parent::render($e);
    }
}
