<?php

namespace app\admin\model;

use think\Model;
use think\Request;

class ViewAdminOrder extends Model
{

    /**
     * 订单列表
     *
     * @param String $page
     * @return json
     */
    public function orderList($page)
    {
        $model = new ViewAdminOrder();
        $data = $model->limit(($page - 1) * 10, 10)
            ->select();
        return json([
            'code'  => 200,
            'data'  => $data
        ]);
    }

    /**
     * 模糊查询订单
     *
     * @param Request $request
     * @return json
     */
    public function findOrderFuzzy(Request $request)
    {
        $order_id = $request->param('order_id');
        $nick_name = $request->param('nick_name');
        $receiver_name = $request->param('receiver_name');
        $province = $request->param('province');

        $ViewAdminOrder = new ViewAdminOrder();
        $data = $ViewAdminOrder->where('order_id', 'like', '%' . $order_id . '%')
            ->where('nick_name', 'like', '%' . $nick_name . '%')
            ->where('receiver_name', 'like', '%' . $receiver_name . '%')
            ->where('province', 'like', '%' . $province . '%')
            ->select();
        return json([
            'code'  => 200,
            'data'  => $data,
        ]);
    }
}
