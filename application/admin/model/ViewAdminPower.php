<?php

namespace app\admin\model;

use think\Model;
use think\exception\ValidateException;

class ViewAdminPower extends Model
{
    function powervalidate($aid, $controller, $action)
    {
        $model = new ViewAdminPower();
        if (empty($aid)) {
            throw new ValidateException('登录信息失效                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                     ');
         }
        $hasPower = $model->where([
            'admin_id'      => $aid,
            'controller'    => $controller,
            'action'        => $action,
        ])->count();
        if (!$hasPower) {
            throw new ValidateException('permission denied');
        }
    }
}
