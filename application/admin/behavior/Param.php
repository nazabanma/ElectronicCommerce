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
        $params = Request::instance()->param();
        $module = Request::instance()->module();
        $controller = Request::instance()->controller();
        $class = 'app\\' . $module . '\\' . 'controller\\' . $controller;
        $method = Request::instance()->action();
        $reflection = new \ReflectionMethod($class, $method);
        $funcParam = $reflection->getParameters();
        if ($funcParam[0]->name == 'request') {
            return;
        }
        for ($i = 0; $i < $reflection->getNumberOfRequiredParameters(); $i++) {
            $value = $params[$funcParam[$i]->name];
            if (is_null($value) || $value == '') {
                dump('test');
                throw new InvalidArgumentException('Necessary param ' . $funcParam[$i]->name . ' is null', 404);
            }
        }
    }
}
