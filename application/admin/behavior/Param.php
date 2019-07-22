<?php

namespace app\admin\behavior;

use think\Request;

use InvalidArgumentException;

class Param
{
    public function run()
    {
        $this->paramAuth();
    }

    private function paramAuth()
    {
        //请求中所有字段
        $params = Request::instance()->param();
        //访问的模型名
        $module = Request::instance()->module();
        //访问的控制器名
        $controller = Request::instance()->controller();
        //完整类名,用于反射
        $class = 'app\\' . $module . '\\' . 'controller\\' . $controller;
        //访问的方法名
        $method = Request::instance()->action();
        //反射
        $reflection = new \ReflectionMethod($class, $method);
        //特殊情况:如果action使用Request $request传参,则跳过验证
        $funcParam = $reflection->getParameters();
        if (empty($funcParam) || $funcParam[0]->name == 'request') {
            return;
        }
        //检测所有的必须参数
        for ($i = 0; $i < $reflection->getNumberOfRequiredParameters(); $i++) {
            //请求中该参数的值
            $value = $params[$funcParam[$i]->name];
            //如果为空或者为'',则抛出异常
            if (is_null($value) || $value == '') {
                throw new InvalidArgumentException('Necessary param ' . $funcParam[$i]->name . ' is null', 404);
            }
        }
    }
}
