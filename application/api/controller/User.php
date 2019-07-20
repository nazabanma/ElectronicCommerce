<?php

namespace app\api\controller;

use think\Controller;
use think\Request;
use app\api\model\Address;
use app\api\model\ViewMyCollect;
use app\api\model\ViewMyOrder;
use app\api\model\ViewMyAddress;
use app\api\model\ViewShopCart;

class User extends Controller
{

    private $User;

    function _initialize()
    {
        $this->User = new \app\api\model\User();
    }
    /**
     * 查询用户
     *
     * @param String $user_id
     * @return json 用户
     */
    public function userInfo($user_id)
    {
        return $this->User->userInfo($user_id);
    }
}
