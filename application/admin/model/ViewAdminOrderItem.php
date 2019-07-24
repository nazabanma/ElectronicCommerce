<?php
namespace app\admin\model;

use think\Model;

class ViewAdminOrderItem extends Model
{

    public function getOrderItem($order_id)
    {
        $model = new ViewAdminOrderItem();
        $data = $model->where('order_id', $order_id)
            ->select();
        return json([
            'code'  => 200,
            'data'  => $data,
        ]);
    }
}
