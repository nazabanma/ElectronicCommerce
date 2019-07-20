<?php

namespace app\admin\model;

use think\Model;

class Admin extends Model
{
    public function login($admin_id, $pwd)
    {
        $model = new Admin();
        $exits = $model->where('admin_id', $admin_id)
            ->where('pwd', $pwd)
            ->count();
        if (!$exits) {
            return json([
                'code'  => '403',
                'msg'   => 'user is not exited'
            ]);
        }
        session('aid', $admin_id);
        return json([
            'code'  => '200',
            'msg'   => 'success'
        ]);
    }
}
