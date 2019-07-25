<?php

namespace app\admin\controller;

use app\admin\model\Admin;
use app\admin\common\base\BaseController;
use think\Request;

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
     * 用户退出,删除session
     *
     * @return json
     */
    public function logOut()
    {
        session('aid', null);
        return json([
            'code'  => 200,
            'msg'   => 'success',
        ]);
    }

    /**
     * 查询用户列表
     *
     * @return json
     */
    public function userList($page)
    {
        return \app\admin\model\User::userList($page);
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

    /**
     * 模糊查询
     *
     * @param Request $request
     * @return json
     */
    public function findUserFuzzy(Request $request)
    {
        $model = new \app\admin\model\User();
        return $model->findUserFuzzy($request);
    }
}
