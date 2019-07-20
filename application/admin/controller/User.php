<?php

namespace app\admin\controller;

use app\admin\model\Admin;
use app\admin\common\base\BaseController;

class User extends BaseController
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
        return $model->login($admin_id, $pwd);
    }

    /**
     * 查询用户列表
     *
     * @return json
     */
    public function userList()
    {
        return \app\admin\model\User::userList();
    }

    /**
     * 查找某用户具体信息
     *
     * @param String $user_id
     * @return json
     */
    public function findUser($user_id)
    {
        return \app\admin\model\User::findUser($user_id);
    }

    /**
     * 删除指定用户
     *
     * @param String $user_id
     * @return json
     */
    public static function delUser($user_id)
    {
        return \app\admin\model\User::delUser($user_id);
    }
}
