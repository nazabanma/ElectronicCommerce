<?php

namespace app\admin\controller;

use app\admin\model\Admin;
use app\admin\common\base\BaseController;
use app\admin\model\User;

class Index extends BaseController
{
    public function index()
    {
        return "<h1>那咋办嘛小组御用后台</h1><img src='http://wyxs.talesrunner.org/static/nazabanma/index.png'/>";
    }
    public function login($admin_id, $pwd)
    {
        $model = new Admin();
        return $model->login($admin_id, $pwd);
    }

    public function userList()
    {
        return User::userList();
    }

    public function findUser($user_id)
    {
        return User::findUser($user_id);
    }
}
