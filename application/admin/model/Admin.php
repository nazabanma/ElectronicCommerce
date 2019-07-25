<?php

namespace app\admin\model;

use think\Model;

class Admin extends Model
{

    /**
     * 用户登录
     *
     * @param String $admin_id
     * @param String $pwd
     * @return json
     */
    public function login($admin_id, $pwd)
    {
        $model = new Admin();
        $exits = $model->where('admin_id', $admin_id)
            ->where('pwd', pwdEncode($pwd))
            ->count();
        if (!$exits) {
            return json([
                'code'  => '403',
                'msg'   => 'Account or Password is wrong'
            ]);
        }
        session('aid', $admin_id);
        return json([
            'code'  => '200',
            'msg'   => 'success'
        ]);
    }
}
