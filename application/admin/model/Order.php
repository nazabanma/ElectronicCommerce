<?php

namespace app\admin\model;

use think\Model;


class Order extends Model
{
    /**
     * 更新订单状态
     *
     * @param String $order_id
     * @param String $order_state_id
     * @return json
     */
    public function updateOrderState($order_id, $order_state_id)
    {
        $model = Order::get($order_id);
        $model->order_state_id = $order_state_id;
        $res = $model->save();
        if ($res == 1) {
            return json([
                'code'  => 200,
                'msg'   => 'success'
            ]);
        }
        return json([
            'code'  => 500,
            'msg'   => 'update failed'
        ]);
    }
}
