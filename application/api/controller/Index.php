<?php

namespace app\api\controller;

use app\api\model\User;
use think\Controller;
use app\api\model\ShopCart;
use app\api\model\ViewMyAddress;

class Index extends Controller
{
    public function index()
    {
        return 'api-index';
    }
    public function test()
    {
        $model = new User();
        $model->data([
            'uid' => '2',
            'nick_name' => 'nikko',
            'gender' => '1',
            'province' => '福建',
            'city' => '厦门',
            'country' => 'China',
            'login_time' => time(),
        ]);

        $model->save();
    }

    public function test2()
    {
        $model = ShopCart::hasWhere('user', ['shopcart.user_id' => '2'])->select();

        return json($model);
    }

    public function test3()
    {
        $model = User::get(1);
        return json($model->shopCart);
    }

    public function test4()
    {
        $model = new ViewMyAddress();
        return json($model->where('user_id', '1')->select());
    }
}
