<?php

namespace app\admin\common\exception;

use Exception;
use think\exception\Handle;
use think\exception\ValidateException;
use think\exception\HttpException;
use InvalidArgumentException;

class Http extends Handle
{

    public function render(Exception $e)
    {



        // 参数验证错误
        if ($e instanceof ValidateException) {

            return json([
                'code'  => 403,
                'msg'   => $e->getError(),
            ]);
            // return view('index/userList', [
            //     'code'  => 403,
            //     'msg'   => $e->getError(),
            // ]);
        }

        // 请求异常
        if ($e instanceof InvalidArgumentException) {
            return json([
                'code'  => $e->getCode(),
                'msg'   => $e->getMessage(),
            ]);
        }

        //TODO::开发者对异常的操作
        //可以在此交由系统处理
        return parent::render($e);
    }
}
