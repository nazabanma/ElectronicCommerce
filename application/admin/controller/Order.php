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
    public function orderList($page)
    {
        return $this->ViewAdminOrder->orderList($page);
    }

    public function findOrderFuzzy(Request $request)
    {
        return $this->ViewAdminOrder->findOrderFuzzy($request);
    }

    public function getOrderItem($order_id)
    {
        return $this->ViewAdminOrderItem->getOrderItem($order_id);
    }

    public function updateOrderState($order_id, $order_state_id)
    {
        return $this->Order->updateOrderState($order_id, $order_state_id);
    }
}
