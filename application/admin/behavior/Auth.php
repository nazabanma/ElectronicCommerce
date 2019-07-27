<?php

namespace app\admin\behavior;

use think\Request;
use think\Config;
use app\admin\model\ViewAdminPower;

class Auth
{
    public function run()
    {
        $this->adminAuth();
    }

    private function adminAuth()
    {
        $controller = Request::instance()->controller();
        $action     = Request::instance()->action();
        $route = ['c' => $controller, 'a' => $action];
        $noAuth = Config::load("config.php")['no_auth'];
        foreach ($noAuth as $tmp) {
            if ($tmp['c'] == $route['c'] && $tmp['a'] == $route['a']) {
                return;
            }
        }
        $aid = Session('aid');
        $model = new ViewAdminPower();
        $model->powervalidate($aid, $controller, $action);
    }
}
