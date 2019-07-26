<?php

namespace app\api\controller;

use think\Controller;
use think\Request;
use app\api\model\ViewMyAddress;
 

class Address extends Controller
{

    /**
     * 查询所有地址
     *
     * @param String $user_id
     * @return json 收货地址列表
     */
    public function myAddress($user_id)
    {
        $model = new ViewMyAddress();
        return $model->myAddress($user_id);
    }

    /**
     * 编辑收货地址
     *
     * @param Request $request
     * @return json 编辑结果
     */
    public function editAddress(\think\Request $request)
    {
        return  \app\api\model\Address::editAddress($request);
    }

     /**
     * 添加地址
     *
     * @param Request $request
     * @return String
     */
    public function addAddress(Request $request)
    {
        return  \app\api\model\Address::addAddress($request);
    }
}