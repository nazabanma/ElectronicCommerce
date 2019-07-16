<?php

namespace app\api\controller;

use think\Controller;
use think\Request;
use app\api\model\Address;
use app\api\model\ViewMyCollect;
use app\api\model\ViewMyOrder;
use app\api\model\ViewMyAddress;

class User extends Controller
{



    public function login(Request $request)
    {
        $username = $request->param('username');
        $password = $request->param('password');

        if (empty($password) || empty($username)) {
            return json(["code" => 400, "msg" => "用户名或密码为空"]);
        }

        $User = new \app\api\model\User();
        $res = $User
            ->field("uid,password")
            ->where('username', $username)
            ->find();

        if (!$res) {
            return json(['code' => 404, 'msg' => '用户不存在']);
        }
        if ($res->password != $password) {
            return json(['code' => 401, 'msg' => '密码错误']);
        }
        if ($res->password == $password) {
            return json(['code' => 200, 'msg' => 'ok', 'data' => $res->uid]);
        }
    }
    /**
     * 查询所有收藏
     *
     * @param String $user_id 用户user_id
     * @return json
     */
    public function myCollect($user_id)
    {
        $Collect = new ViewMyCollect();
        $data = $Collect->where('user_id', $user_id)->select();
        return json([
            'code' => '200',
            'data' => $data
        ]);
    }

    /**
     * 查询所有订单
     *
     * @param String $user_id
     * @return json
     */
    public function myOrder($user_id)
    {
        $Order = new ViewMyOrder();
        $data = $Order->where('user_id', $user_id)->select();
        return json([
            'code' => '200',
            'data' => $data
        ]);
    }

    /**
     * 查询所有地址
     *
     * @param String $user_id
     * @return json
     */
    public function myAddress($user_id)
    {
        $Address = new ViewMyAddress();
        $data = $Address->where('user_id', $user_id)->select();
        return json([
            'code' => '200',
            'data' => $data
        ]);
    }

    public function editAddress(Request $request)
    {
        $address_id = $request->param('address_id');
        if (empty($address_id)) {
            return json([
                'code' => 404,
                'msg' => 'id为空'
            ]);
        }
        $Address = Address::get($address_id);
        if (!$Address) {
            return json([
                'code' => 404,
                'msg' => '该记录不存在'
            ]);
        }
        //通过两次键值翻转,去除数组中的索引部分,否则更新数据时会将索引识别为字段
        $data = array_flip(array_flip($request->param()));
        $res = $Address->save($data);
        if ($res == 0) {
            return json([
                'code' => 500,
                'msg' => 'update failed'
            ]);
        }
        return json([
            'code' => 200,
            'msg' => 'success'
        ]);
    }
}
