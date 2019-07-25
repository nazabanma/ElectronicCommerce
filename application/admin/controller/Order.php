<?php

namespace app\admin\controller;

use app\admin\common\base\BaseController;
use app\admin\model\ViewAdminOrder;
use think\Request;
use app\admin\model\ViewAdminOrderItem;

class Order extends BaseController
{
    private $Order;
    private $ViewAdminOrder;
    private $ViewAdminOrderItem;

    function _initialize()
    {
        $this->Order = new \app\admin\model\Order();
        $this->ViewAdminOrder = new ViewAdminOrder();
        $this->ViewAdminOrderItem = new ViewAdminOrderItem();
    }

    /**
     * 订单列表
     *
     * @param String $page
     * @return json
     */
    public function orderList($page)
    {
        return $this->ViewAdminOrder->orderList($page);
    }

    /**
     * 模糊查询订单
     *
     * @param Request $request
     * @return json
     */
    public function findOrderFuzzy(Request $request)
    {
        return $this->ViewAdminOrder->findOrderFuzzy($request);
    }

    /**
     * 查询订单项
     *
     * @param String $order_id
     * @return json
     */
    public function getOrderItem($order_id)
    {
        return $this->ViewAdminOrderItem->getOrderItem($order_id);
    }

    /**
     * 更新订单状态
     *
     * @param String $order_id
     * @param String $order_state_id
     * @return json
     */
    public function updateOrderState($order_id, $order_state_id)
    {
        return $this->Order->updateOrderState($order_id, $order_state_id);
    }
}
