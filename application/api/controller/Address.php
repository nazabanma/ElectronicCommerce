<?php

namespace app\api\controller;

use think\Controller;
use think\Request;
use app\api\model\ViewMyAddress;


class Address extends Controller
{

    private $Address;

    function _initialize()
    {
        $this->Address = new \app\api\model\Address();
    }

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
        return $this->Address->editAddress($request);
    }

    /**
     * 添加地址
     *
     * @param Request $request
     * @return String
     */
    public function addAddress(Request $request)
    {
        return $this->Address->addAddress($request);
    }

    /**
     * 删除地址
     *
     * @param String $address_id
     * @return json 删除结果
     */
    public function deleteAddress($address_id)
    {
        return $this->Address->deleteAddress($address_id);
    }

     /**
     * 查询地址
     *
     * @param String $address_id
     * @return json 查询结果
     */
    public function findAddress($address_id)
    {
        return $this->Address->findAddress($address_id);
    }
}
