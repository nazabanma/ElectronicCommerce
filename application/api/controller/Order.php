<?php

namespace app\api\controller;

use think\Controller;
use think\Request;
use app\api\model\Evaluate;
use app\api\model\ViewMyOrder;

class Order extends Controller
{

    private $Order;

    function _initialize()
    {
        $this->Order = new \app\api\model\Order();
    }

     /**
     * 查询所有订单
     *
     * @param String $user_id
     * @return json 订单信息
     */
    public function myOrder($user_id)
    {
        $model = new ViewMyOrder();
        return $model->myOrder($user_id);
    }

    public function allOrder($user_id)
    {
        return $this->Order->allOrder();
    }

    /**
     * 根据请求创建订单(待支付)
     *
     * @param String $request
     * @return 购买信息
     */
    public function orderCreate(\think\Request $request)
    {
        return $this->Order->orderCreate($request);
    }

    /**
     * 修改订单地址信息(要在发货前修改)
     *
     * @param Request $request
     * @return void
     */
    public function orderUpdate(\think\Request $request)
    {
        return $this->Order->orderUpdate($request);
    }

    /**
     * 修改订单状态（用户确认收货，商家发货）
     *
     * @param Request $request
     * @return void
     */
    public function orderStateUpdate(Request $request)
    {
        return $this->Order->orderStateUpdate($request);
    }


    /**
     * 用户查看订单列表删除订单
     *
     * @param Request $request
     * @return void
     */
    public function orderDelete(Request $request)
    {
        return $this->orderDelete($request);
    }

    /**
     * 用户取消订单
     *
     * @param Request $request
     * @return void
     */
    public function orderCancel(Request $request)
    {
        return $this->Order->orderCancel($request);
    }

    


    
}
