<?php

namespace app\admin\behavior;

use think\Request;

use InvalidArgumentException;

class Param
{
    public function run(&$info)
    {
        $this->paramAuth();
    }

    private function paramAuth()
    {
        $module = Request::instance()->module();
        $controller = Request::instance()->controller();
        $class = 'app\\' . $module . '\\' . 'controller\\' . $controller;
        $method = Request::instance()->action();
        $reflection = new \ReflectionMethod($class, $method);
        $allParam = $reflection->getParameters();
        for ($i = 0; $i < $reflection->getNumberOfRequiredParameters(); $i++) {
            $value = $_REQUEST[$allParam[$i]->name];
            if (is_null($value) || $value == '') {
                throw new InvalidArgumentException('Necessary param ' . $allParam[$i]->name . ' is null', 404);
            }
        }
    }
}
